<form method="GET" class="card mb-5 grid grid-cols-1 md:grid-cols-6 gap-3 items-end print:hidden">
    <div>
        <label class="form-label">Category</label>
        <select name="category_id" class="form-input">
            <option value="">All</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Medicine</label>
        <select name="medicine_id" class="form-input">
            <option value="">All</option>
            @foreach ($medicines as $m)
                <option value="{{ $m->id }}" @selected(request('medicine_id') == $m->id)>{{ $m->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Stock Status</label>
        <select name="stock_status" class="form-input">
            <option value="">All</option>
            <option value="in_stock" @selected(request('stock_status')=='in_stock')>In Stock</option>
            <option value="low_stock" @selected(request('stock_status')=='low_stock')>Low Stock</option>
            <option value="out_of_stock" @selected(request('stock_status')=='out_of_stock')>Out of Stock</option>
        </select>
    </div>
    <div>
        <label class="form-label">Expiry Status</label>
        <select name="expiry_status" class="form-input">
            <option value="">All</option>
            <option value="valid" @selected(request('expiry_status')=='valid')>Valid</option>
            <option value="near_expiry" @selected(request('expiry_status')=='near_expiry')>Near Expiry</option>
            <option value="expired" @selected(request('expiry_status')=='expired')>Expired</option>
        </select>
    </div>
    <div>
        <label class="form-label">From</label>
        <input type="date" name="from" value="{{ request('from') }}" class="form-input">
    </div>
    <div>
        <label class="form-label">To</label>
        <input type="date" name="to" value="{{ request('to') }}" class="form-input">
    </div>
    <div class="md:col-span-6 flex gap-2">
        <button class="btn-primary">Apply Filters</button>
        <a href="{{ url()->current() }}" class="btn-secondary">Reset</a>
        <a href="{{ route('reports.export', array_merge(request()->query(), ['type' => $reportType])) }}" class="btn-secondary ml-auto">Export CSV</a>
        <button type="button" onclick="window.print()" class="btn-secondary">Print Report</button>
    </div>
</form>
