{{-- resources/views/livewire/tickets/finance/partials/line-items.blade.php --}}

<div x-data="{
    transactions: @entangle('transactions').live,
    uoms: @js($uoms->toArray()),

    addLine() {
        $wire.addLineItem();
    },

    removeLine(index) {
        if (this.transactions.length > 1) {
            $wire.removeLineItem(index);
        }
    },

    duplicateLine(index) {
        $wire.duplicateLineItem(index);
    },

    calculateLineTotal(index) {
        let item = this.transactions[index];
        if (item) {
            item.total_cost = (parseFloat(item.qty) || 0) * (parseFloat(item.unit_cost) || 0);
            $wire.calculateTotals();
        }
    }
}">

    {{-- Header with Add Button --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Line Items</h5>
            <p class="mb-0 text-muted small">Add services or items with quantities and prices</p>
        </div>
        <button type="button"
                @click="addLine()"
                class="btn btn-primary btn-sm">
            <i class="mdi mdi-plus me-1"></i> Add Line Item
        </button>
    </div>

    {{-- Desktop Table View --}}
    <div class="table-responsive d-none d-lg-block">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px;">#</th>
                    <th style="width: 40%;">Description <span class="text-danger">*</span></th>
                    <th style="width: 10%;">Qty <span class="text-danger">*</span></th>
                    <th style="width: 15%;">UOM <span class="text-danger">*</span></th>
                    <th style="width: 12%;">Unit Cost <span class="text-danger">*</span></th>
                    <th style="width: 13%;">Total</th>
                    <th style="width: 10%;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $index => $item)
                    <tr wire:key="transaction-{{ $item['temp_id'] }}">
                        {{-- Sr No --}}
                        <td class="text-center align-middle">
                            <span class="fw-bold">{{ $index + 1 }}</span>
                        </td>

                        {{-- Description --}}
                        <td>
                            <textarea
                                wire:model.live.debounce.300ms="transactions.{{ $index }}.description"
                                class="form-control form-control-sm"
                                rows="1"
                                placeholder="Enter service description..."
                                maxlength="500"></textarea>
                            @error("transactions.{$index}.description")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </td>

                        {{-- Quantity --}}
                        <td>
                            <input type="number"
                                   wire:model.live.debounce.300ms="transactions.{{ $index }}.qty"
                                   wire:change="calculateLineItemTotal({{ $index }})"
                                   class="form-control form-control-sm text-end"
                                   step="0.001"
                                   min="0.001"
                                   placeholder="1.000">
                            @error("transactions.{$index}.qty")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </td>

                        {{-- UOM --}}
                        <td>
                            <div class="input-group input-group-sm">
                                <select wire:model.live="transactions.{{ $index }}.uom_id"
                                        class="form-select form-select-sm">
                                    <option value="">-- Select --</option>
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->id }}">{{ $uom->code }}</option>
                                    @endforeach
                                </select>
                                <button type="button"
                                        wire:click="openQuickAddUOM"
                                        class="btn btn-outline-secondary btn-sm"
                                        title="Add UOM">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                            @error("transactions.{$index}.uom_id")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </td>

                        {{-- Unit Cost --}}
                        <td>
                            <input type="number"
                                   wire:model.live.debounce.300ms="transactions.{{ $index }}.unit_cost"
                                   wire:change="calculateLineItemTotal({{ $index }})"
                                   class="form-control form-control-sm text-end"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                            @error("transactions.{$index}.unit_cost")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </td>

                        {{-- Total Cost (Calculated) --}}
                        <td class="align-middle">
                            <strong class="text-primary">{{ number_format($item['total_cost'], 2) }}</strong>
                        </td>

                        {{-- Actions --}}
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm">
                                <button type="button"
                                        wire:click="duplicateLineItem({{ $index }})"
                                        class="btn btn-outline-info"
                                        title="Duplicate">
                                    <i class="mdi mdi-content-copy"></i>
                                </button>
                                <button type="button"
                                        wire:click="removeLineItem({{ $index }})"
                                        class="btn btn-outline-danger"
                                        title="Remove"
                                        @if(count($transactions) === 1) disabled @endif>
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="d-lg-none">
        @foreach($transactions as $index => $item)
            <div class="mb-3 shadow-sm card" wire:key="transaction-mobile-{{ $item['temp_id'] }}">
                <div class="py-2 card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Item #{{ $index + 1 }}</span>
                    <div class="btn-group btn-group-sm">
                        <button type="button"
                                wire:click="duplicateLineItem({{ $index }})"
                                class="btn btn-outline-info btn-sm"
                                title="Duplicate">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                        <button type="button"
                                wire:click="removeLineItem({{ $index }})"
                                class="btn btn-outline-danger btn-sm"
                                title="Remove"
                                @if(count($transactions) === 1) disabled @endif>
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold">
                            Description <span class="text-danger">*</span>
                        </label>
                        <textarea
                            wire:model.live.debounce.300ms="transactions.{{ $index }}.description"
                            class="form-control form-control-sm"
                            rows="3"
                            placeholder="Enter service description..."
                            maxlength="500"></textarea>
                    </div>

                    {{-- Quantity & UOM Row --}}
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                Qty <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   wire:model.live.debounce.300ms="transactions.{{ $index }}.qty"
                                   wire:change="calculateLineItemTotal({{ $index }})"
                                   class="form-control form-control-sm text-end"
                                   step="0.001"
                                   min="0.001"
                                   placeholder="1.000">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                UOM <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <select wire:model.live="transactions.{{ $index }}.uom_id"
                                        class="form-select form-select-sm">
                                    <option value="">-- Select --</option>
                                    @foreach($uoms as $uom)
                                        <option value="{{ $uom->id }}">{{ $uom->code }}</option>
                                    @endforeach
                                </select>
                                <button type="button"
                                        wire:click="openQuickAddUOM"
                                        class="btn btn-outline-secondary btn-sm">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Unit Cost & Total Row --}}
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                Unit Cost <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   wire:model.live.debounce.300ms="transactions.{{ $index }}.unit_cost"
                                   wire:change="calculateLineItemTotal({{ $index }})"
                                   class="form-control form-control-sm text-end"
                                   step="0.01"
                                   min="0"
                                   placeholder="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Total</label>
                            <div class="p-2 rounded text-end bg-light">
                                <strong class="text-primary fs-5">{{ number_format($item['total_cost'], 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Add Line Button (Mobile) --}}
        <button type="button"
                wire:click="addLineItem"
                class="mb-3 btn btn-primary w-100">
            <i class="mdi mdi-plus me-1"></i> Add Another Line Item
        </button>
    </div>

    {{-- Validation Errors --}}
    @error('transactions')
        <div class="mt-3 alert alert-danger">{{ $message }}</div>
    @enderror

    {{-- Summary Info --}}
    <div class="mt-3 alert alert-info d-flex justify-content-between align-items-center">
        <span>
            <i class="mdi mdi-information-outline me-1"></i>
            Total Line Items: <strong>{{ count($transactions) }}</strong>
        </span>
        <span class="fw-bold">
            Subtotal: {{ \App\Enums\Currency::from($currency)->symbol() }}{{ number_format($subtotal, 2) }}
        </span>
    </div>

</div>

{{-- Keyboard Shortcuts Help --}}
<div class="mt-3 text-muted small">
    <i class="mdi mdi-keyboard-outline me-1"></i>
    <strong>Tips:</strong> Use Tab to navigate between fields. Totals calculate automatically.
</div>

