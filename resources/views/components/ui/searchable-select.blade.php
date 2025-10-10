{{-- resources/views/components/searchable-select.blade.php --}}

@props([
    'id',
    'wireModel',
    'options' => [],
    'placeholder' => 'Search...',
    'disabled' => false,
])

<div
    x-data="{ open: false, searchQuery: '', displayText: '', placeholder: '{{ $placeholder }}', selectedValue: @entangle($wireModel).live, disabled: {{ $disabled ? 'true' : 'false' }}, options: {{ Js::from($options) }}, filteredOptions: {{ Js::from($options) }}, init() { this.updateDisplayText(); this.$watch('selectedValue', () => this.updateDisplayText()); }, handleFocus() { if (!this.disabled) { this.open = true; this.displayText = this.searchQuery; this.searchQuery = ''; this.filterOptions(); } }, handleBlur() { setTimeout(() => { this.open = false; if (!this.open) { this.searchQuery = this.displayText; } }, 200); }, filterOptions() { const query = this.searchQuery.toLowerCase().trim(); this.filteredOptions = query === '' ? this.options : this.options.filter(o => o.label.toLowerCase().includes(query)); }, selectOption(option) { this.selectedValue = option.value; this.searchQuery = option.label; this.displayText = option.label; this.open = false; }, updateDisplayText() { if (this.selectedValue) { const selected = this.options.find(o => o.value == this.selectedValue); if (selected) { this.searchQuery = selected.label; this.displayText = selected.label; } else { this.searchQuery = ''; this.displayText = ''; } } else { this.searchQuery = ''; this.displayText = ''; } } }"
    x-init="init()"
    class="searchable-select-wrapper"
    wire:key="select-{{ $id }}-{{ md5(json_encode($options)) }}"
>
    <div class="searchable-select-container">
        <input
            type="text"
            class="form-control searchable-select-input"
            :class="{ 'disabled': disabled }"
            x-model="searchQuery"
            @focus="handleFocus()"
            @input="filterOptions()"
            @blur="handleBlur()"
            :placeholder="placeholder"
            :disabled="disabled"
            autocomplete="off">

        <div class="searchable-select-dropdown" x-show="open" x-transition style="display: none;">
            <template x-if="filteredOptions.length === 0">
                <div class="searchable-select-option no-results">No results found</div>
            </template>

            <template x-for="option in filteredOptions" :key="option.value">
                <div
                    class="searchable-select-option"
                    :class="{ 'selected': option.value == selectedValue }"
                    @click="selectOption(option)"
                    x-text="option.label">
                </div>
            </template>
        </div>
    </div>
</div>

<style>
.searchable-select-wrapper {
    position: relative;
    width: 100%;
}

.searchable-select-container {
    position: relative;
}

/* Base Input Style */
.searchable-select-input {
    width: 100%;
    padding: 0.45rem 2.5rem 0.45rem 0.9rem;
    border-radius: 0.25rem;
    cursor: text;
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
}

/* Dark Mode */
@media (prefers-color-scheme: dark) {
    .searchable-select-input {
        background-color: #37404a;
        border: 1px solid #404954;
        color: #aab8c5;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23aab8c5' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }

    .searchable-select-input:focus {
        border-color: #727cf5;
        box-shadow: 0 0 0 0.2rem rgba(114, 124, 245, 0.25);
        outline: none;
        background-color: #37404a;
    }

    .searchable-select-input.disabled {
        background-color: #2c333b;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .searchable-select-dropdown {
        background-color: #37404a;
        border: 1px solid #404954;
    }

    .searchable-select-option {
        color: #aab8c5;
    }

    .searchable-select-option:hover {
        background-color: #727cf5;
        color: #ffffff;
    }

    .searchable-select-option.selected {
        background-color: #2c333b;
        color: #727cf5;
    }

    .searchable-select-option.no-results {
        color: #6c757d;
    }
}

/* Light Mode */
@media (prefers-color-scheme: light), (prefers-color-scheme: no-preference) {
    .searchable-select-input {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        color: #212529;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23212529' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }

    .searchable-select-input:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: none;
        background-color: #ffffff;
    }

    .searchable-select-input.disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .searchable-select-dropdown {
        background-color: #ffffff;
        border: 1px solid #dee2e6;
    }

    .searchable-select-option {
        color: #212529;
    }

    .searchable-select-option:hover {
        background-color: #0d6efd;
        color: #ffffff;
    }

    .searchable-select-option.selected {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .searchable-select-option.no-results {
        color: #6c757d;
    }
}

/* Ubold Theme Override - Dark Mode */
[data-bs-theme="dark"] .searchable-select-input {
    background-color: #37404a !important;
    border-color: #404954 !important;
    color: #aab8c5 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23aab8c5' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

[data-bs-theme="dark"] .searchable-select-input:focus {
    border-color: #727cf5 !important;
    box-shadow: 0 0 0 0.2rem rgba(114, 124, 245, 0.25) !important;
}

[data-bs-theme="dark"] .searchable-select-dropdown {
    background-color: #37404a !important;
    border-color: #404954 !important;
}

[data-bs-theme="dark"] .searchable-select-option {
    color: #aab8c5 !important;
}

[data-bs-theme="dark"] .searchable-select-option:hover {
    background-color: #727cf5 !important;
    color: #ffffff !important;
}

/* Ubold Theme Override - Light Mode */
[data-bs-theme="light"] .searchable-select-input,
body:not([data-bs-theme]) .searchable-select-input {
    background-color: #ffffff !important;
    border-color: #dee2e6 !important;
    color: #212529 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23212529' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
}

[data-bs-theme="light"] .searchable-select-input:focus {
    border-color: #86b7fe !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
}

[data-bs-theme="light"] .searchable-select-dropdown {
    background-color: #ffffff !important;
    border-color: #dee2e6 !important;
}

[data-bs-theme="light"] .searchable-select-option:hover {
    background-color: #0d6efd !important;
    color: #ffffff !important;
}

/* Common Styles */
.searchable-select-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 300px;
    overflow-y: auto;
    border-radius: 0.25rem;
    margin-top: 4px;
    z-index: 1050;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.searchable-select-option {
    padding: 0.5rem 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.searchable-select-option.no-results {
    text-align: center;
    cursor: default;
}

.searchable-select-option.no-results:hover {
    background-color: transparent !important;
}

/* Input group support */
.input-group .searchable-select-wrapper {
    flex: 1 1 auto;
    width: 1%;
    min-width: 0;
}

.input-group .searchable-select-input {
    border-right: 0;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

/* Scrollbar */
.searchable-select-dropdown::-webkit-scrollbar {
    width: 8px;
}

.searchable-select-dropdown::-webkit-scrollbar-track {
    background: transparent;
}

.searchable-select-dropdown::-webkit-scrollbar-thumb {
    background: #6c757d;
    border-radius: 4px;
}
</style>
