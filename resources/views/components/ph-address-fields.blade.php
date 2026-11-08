{{-- 
    Cascading Philippine Address Dropdowns Component
    
    Usage: <x-ph-address-fields 
        :province="old('address_province', $model->address_province ?? '')"
        :city="old('address_city', $model->address_city ?? '')"  
        :barangay="old('address_barangay', $model->address_barangay ?? '')"
        :street="old('address_street', $model->address_street ?? '')"
        :input-class="'w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500'"
        :compact="false"
    />
--}}

@props([
    'province' => '',
    'city' => '',
    'barangay' => '',
    'street' => '',
    'inputClass' => 'w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500',
    'compact' => false,
    'required' => true,
])

<div x-data="phAddress({
    initialProvince: '{{ addslashes($province) }}',
    initialCity: '{{ addslashes($city) }}',
    initialBarangay: '{{ addslashes($barangay) }}',
})" x-init="init()">
    <div class="grid grid-cols-1 {{ $compact ? 'gap-2' : 'md:grid-cols-2 gap-4' }}">
        {{-- Province --}}
        <div>
            <label for="ph-address_province" class="block text-{{ $compact ? 'xs' : 'sm' }} font-medium text-gray-700 mb-1{{ $compact ? '' : '.5' }}">
                Province {{ $required ? '*' : '' }}
            </label>
            <select id="ph-address_province" name="address_province" 
                x-model="selectedProvinceName" 
                @change="onProvinceChange($event)"
                {{ $required ? 'required' : '' }}
                class="{{ $inputClass }}">
                <option value="">-- Select Province --</option>
                <template x-for="p in provinces" :key="p.id">
                    <option :value="p.name" x-text="p.name" :selected="p.name === selectedProvinceName"></option>
                </template>
            </select>
            @error('address_province') <p class="mt-1 text-{{ $compact ? 'xs' : 'sm' }} text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- City / Municipality --}}
        <div>
            <label for="ph-address_city" class="block text-{{ $compact ? 'xs' : 'sm' }} font-medium text-gray-700 mb-1{{ $compact ? '' : '.5' }}">
                City / Municipality {{ $required ? '*' : '' }}
            </label>
            <select id="ph-address_city" name="address_city" 
                x-model="selectedCityName" 
                @change="onCityChange($event)"
                {{ $required ? 'required' : '' }}
                :disabled="cities.length === 0 && !selectedCityName"
                class="{{ $inputClass }}">
                <option value="">-- Select City/Municipality --</option>
                <template x-for="c in cities" :key="c.id">
                    <option :value="c.name" x-text="c.name" :selected="c.name === selectedCityName"></option>
                </template>
            </select>
            @error('address_city') <p class="mt-1 text-{{ $compact ? 'xs' : 'sm' }} text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Barangay --}}
        <div>
            <label for="ph-address_barangay" class="block text-{{ $compact ? 'xs' : 'sm' }} font-medium text-gray-700 mb-1{{ $compact ? '' : '.5' }}">
                Barangay {{ $required ? '*' : '' }}
            </label>
            <template x-if="barangays.length > 0">
                <select id="ph-address_barangay" name="address_barangay" 
                    x-model="selectedBarangayName"
                    {{ $required ? 'required' : '' }}
                    class="{{ $inputClass }}">
                    <option value="">-- Select Barangay --</option>
                    <template x-for="b in barangays" :key="b.id">
                        <option :value="b.name" x-text="b.name" :selected="b.name === selectedBarangayName"></option>
                    </template>
                </select>
            </template>
            <template x-if="barangays.length === 0">
                <input type="text" id="ph-address_barangay" name="address_barangay" 
                    x-model="selectedBarangayName"
                    {{ $required ? 'required' : '' }}
                    placeholder="Enter barangay"
                    class="{{ $inputClass }}">
            </template>
            @error('address_barangay') <p class="mt-1 text-{{ $compact ? 'xs' : 'sm' }} text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Street Address --}}
        <div>
            <label for="ph-address_street" class="block text-{{ $compact ? 'xs' : 'sm' }} font-medium text-gray-700 mb-1{{ $compact ? '' : '.5' }}">
                Street / House No. / Purok
            </label>
            <input type="text" id="ph-address_street" name="address_street" 
                value="{{ $street }}"
                placeholder="House no., Street, Purok, etc."
                class="{{ $inputClass }}">
            @error('address_street') <p class="mt-1 text-{{ $compact ? 'xs' : 'sm' }} text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Hidden combined home_address field --}}
    <input type="hidden" name="home_address" :value="computedAddress">
</div>

<script>
if (typeof window.phAddressLoaded === 'undefined') {
    window.phAddressLoaded = true;
    
    window.phAddress = function(config) {
        return {
            provinces: [],
            cities: [],
            barangays: [],
            selectedProvinceId: null,
            selectedProvinceName: config.initialProvince || '',
            selectedCityId: null,
            selectedCityName: config.initialCity || '',
            selectedBarangayName: config.initialBarangay || '',

            get computedAddress() {
                let parts = [];
                // Find street input within the same form context
                const form = this.$el.closest('form') || document;
                const street = form.querySelector('input[name="address_street"]');
                if (street && street.value.trim()) parts.push(street.value.trim());
                if (this.selectedBarangayName) parts.push(this.selectedBarangayName);
                if (this.selectedCityName) parts.push(this.selectedCityName);
                if (this.selectedProvinceName) parts.push(this.selectedProvinceName);
                return parts.join(', ');
            },

            async init() {
                await this.loadProvinces();
                
                if (config.initialProvince) {
                    const province = this.provinces.find(p => p.name === config.initialProvince);
                    if (province) {
                        this.selectedProvinceId = province.id;
                        await this.loadCities(province.id);
                        
                        if (config.initialCity) {
                            const city = this.cities.find(c => c.name === config.initialCity);
                            if (city) {
                                this.selectedCityId = city.id;
                                await this.loadBarangays(city.id);
                            }
                        }
                    }
                }
            },

            async loadProvinces() {
                try {
                    const res = await fetch('/api/ph-address/provinces');
                    this.provinces = await res.json();
                } catch (e) {
                    console.error('Failed to load provinces:', e);
                }
            },

            async loadCities(provinceId) {
                try {
                    const res = await fetch(`/api/ph-address/provinces/${provinceId}/cities`);
                    this.cities = await res.json();
                } catch (e) {
                    console.error('Failed to load cities:', e);
                }
            },

            async loadBarangays(cityId) {
                try {
                    const res = await fetch(`/api/ph-address/cities/${cityId}/barangays`);
                    this.barangays = await res.json();
                } catch (e) {
                    console.error('Failed to load barangays:', e);
                }
            },

            async onProvinceChange(event) {
                const name = event.target.value;
                this.selectedProvinceName = name;
                this.selectedCityName = '';
                this.selectedCityId = null;
                this.selectedBarangayName = '';
                this.cities = [];
                this.barangays = [];

                const province = this.provinces.find(p => p.name === name);
                if (province) {
                    this.selectedProvinceId = province.id;
                    await this.loadCities(province.id);
                }
            },

            async onCityChange(event) {
                const name = event.target.value;
                this.selectedCityName = name;
                this.selectedBarangayName = '';
                this.barangays = [];

                const city = this.cities.find(c => c.name === name);
                if (city) {
                    this.selectedCityId = city.id;
                    await this.loadBarangays(city.id);
                }
            },
        }
    }
}
</script>
