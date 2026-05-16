<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetApiController extends Controller
{
    private array $months = [
        'april' => 4,
        'may' => 5,
        'june' => 6,
        'july' => 7,
        'august' => 8,
        'september' => 9,
        'october' => 10,
        'november' => 11,
        'december' => 12,
        'january' => 1,
        'february' => 2,
        'march' => 3,
    ];

    public function annualBudget(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user.',
            ], 401);
        }

        $financialYear = $request->input('financial_year', $this->currentFinancialYear());
        [$monthName, $monthNumber, $year] = $this->resolveMonth($request, $financialYear);

        $budget = Budget::where('user_id', $user->id)
            ->where('financial_year', $financialYear)
            ->first();

        $target = $budget ? (float) ($budget->{$monthName} ?? 0) : 0;
        $achievement = $this->getAchievement($user->id, $monthNumber, $year);
        $achievementPercentage = $target > 0 ? round(($achievement / $target) * 100, 2) : null;

        return response()->json([
            'status' => true,
            'data' => [
                'financial_year' => $financialYear,
                'financial_years' => $this->getFinancialYears($user->id, $financialYear),
                'month' => [
                    'key' => $monthName,
                    'number' => $monthNumber,
                    'year' => $year,
                    'label' => Carbon::createFromDate($year, $monthNumber, 1)->format('M y'),
                ],
                'annual_target' => [
                    'target' => $target,
                    'target_formatted' => $this->formatAmount($target),
                    'achievement' => $achievement,
                    'achievement_formatted' => $this->formatAmount($achievement),
                    'achievement_percentage' => $achievementPercentage,
                    'achievement_percentage_formatted' => $achievementPercentage === null
                        ? '--'
                        : $achievementPercentage . '%',
                ],
            ],
        ]);
    }

    private function currentFinancialYear(): string
    {
        $now = Carbon::now();
        $startYear = $now->month >= 4 ? $now->year : $now->year - 1;

        return $startYear . '-' . substr((string) ($startYear + 1), -2);
    }

    private function resolveMonth(Request $request, string $financialYear): array
    {
        $requestedMonth = (int) $request->input('month', 0);
        $monthNumber = $requestedMonth >= 1 && $requestedMonth <= 12
            ? $requestedMonth
            : $this->defaultMonthForFinancialYear($financialYear);

        $monthName = array_search($monthNumber, $this->months, true) ?: 'april';
        $year = $this->yearForFinancialMonth($financialYear, $monthNumber);

        return [$monthName, $monthNumber, $year];
    }

    private function defaultMonthForFinancialYear(string $financialYear): int
    {
        if ($financialYear === $this->currentFinancialYear()) {
            return Carbon::now()->month;
        }

        return 4;
    }

    private function yearForFinancialMonth(string $financialYear, int $monthNumber): int
    {
        $years = explode('-', $financialYear);
        $startYear = (int) ($years[0] ?? Carbon::now()->year);

        return $monthNumber >= 4 ? $startYear : $startYear + 1;
    }

    private function getAchievement(int $userId, int $monthNumber, int $year): float
    {
        return (float) OrderItem::whereHas('order', function ($query) use ($userId, $monthNumber, $year) {
            $query->where('user_id', $userId)
                ->where('status', 'dispatched')
                ->whereMonth('created_at', $monthNumber)
                ->whereYear('created_at', $year);
        })->sum('grand_total');
    }

    private function getFinancialYears(int $userId, string $selectedFinancialYear): array
    {
        $years = Budget::where('user_id', $userId)
            ->distinct()
            ->orderByDesc('financial_year')
            ->pluck('financial_year')
            ->toArray();

        if (!in_array($selectedFinancialYear, $years, true)) {
            $years[] = $selectedFinancialYear;
        }

        rsort($years);

        return array_values($years);
    }

    private function formatAmount(float $amount): string
    {
        return preg_replace('/\.00$/', '', number_format($amount, 2, '.', ','));
    }
}
