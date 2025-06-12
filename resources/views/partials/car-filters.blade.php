<form id="filter-form" class="bg-white rounded-xl shadow-sm p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Car Type</label>
            <select name="type" id="type" class="form-select">
                <option value="">All Types</option>
                <option value="sedan">Sedan</option>
                <option value="suv">SUV</option>
                <option value="sports">Sports</option>
                <option value="luxury">Luxury</option>
            </select>
        </div>
        
        <div>
            <label for="transmission" class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
            <select name="transmission" id="transmission" class="form-select">
                <option value="">All Transmissions</option>
                <option value="automatic">Automatic</option>
                <option value="manual">Manual</option>
            </select>
        </div>
        
        <div>
            <label for="price_range" class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
            <select name="price_range" id="price_range" class="form-select">
                <option value="">All Prices</option>
                <option value="0-50">$0 - $50</option>
                <option value="50-100">$50 - $100</option>
                <option value="100-200">$100 - $200</option>
                <option value="200+">$200+</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="btn-primary w-full">
                Apply Filters
            </button>
        </div>
    </div>
</form> 