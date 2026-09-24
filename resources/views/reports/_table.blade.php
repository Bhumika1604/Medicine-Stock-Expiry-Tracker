@if ($batches->isEmpty())
    <div class="card"><x-empty-state title="No records match these filters" subtitle="Try widening your date range or clearing a filter." /></div>
@else
    <div class="card p-0 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3 text-left">Medicine</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-left">Batch No.</th>
                    <th class="px-4 py-3 text-left">Expiry Date</th>
                    <th class="px-4 py-3 text-right">Days</th>
                    <th class="px-4 py-3 text-right">Quantity</th>
                    <th class="px-4 py-3 text-left">Stock Status</th>
                    <th class="px-4 py-3 text-left">Expiry Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach ($batches as $batch)
                    <tr class="table-row-hover">
                        <td class="px-4 py-3 font-medium text-ink-800">{{ $batch->medicine->name }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $batch->medicine->category->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $batch->batch_number }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $batch->expiry_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right text-ink-600">{{ $batch->getDaysRemaining() }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ $batch->quantity }}</td>
                        <td class="px-4 py-3"><x-badge :color="$batch->stockBadgeColor()" :label="$batch->getStockStatus()" /></td>
                        <td class="px-4 py-3"><x-badge :color="$batch->expiryBadgeColor()" :label="$batch->getExpiryStatus()" /></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 print:hidden">{{ $batches->links() }}</div>
@endif
