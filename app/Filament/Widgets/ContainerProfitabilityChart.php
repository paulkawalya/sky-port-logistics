<?php

namespace App\Filament\Widgets;

use App\Models\Transit;
use Filament\Widgets\ChartWidget;

class ContainerProfitabilityChart extends ChartWidget
{
    protected static ?string $heading = 'Container Volume & Consignment Density';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        // Top 8 recent containers/transits with shipment counts
        $transits = Transit::withCount('shippments')
            ->latest()
            ->take(8)
            ->get();

        $labels = [];
        $shipmentCounts = [];

        foreach ($transits as $transit) {
            $labels[] = $transit->number ?? "Container #{$transit->id}";
            $shipmentCounts[] = $transit->shippments_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Consignments Loaded',
                    'data' => $shipmentCounts,
                    'backgroundColor' => '#6366f1',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

