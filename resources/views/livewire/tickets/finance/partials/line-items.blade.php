{{-- resources/views/livewire/tickets/finance/partials/line-items.blade.php --}}

<div x-data="{
    transactions: @entangle('transactions'),
    uoms: {{ json_encode($uoms->toArray()) }},

    addLine() {
        this.transactions.push({
            temp_id: crypto.randomUUID(),
            sr_no: this.transactions.length + 1,
            description: '',
            qty: 1,
            uom_id: null,
            unit_cost: 0,
            total_cost: 0
        });
    },

    removeLine(index) {
        if (this.transactions.length > 1) {
            this.transactions.splice(index, 1);
            this.reorderLines();
            $wire.calculateTotals();
        }
    },

    duplicateLine(index) {
        let item = JSON.parse(JSON.stringify(this.transactions[index]));
        item.temp_id = crypto.randomUUID();
        item.sr_no = this.transactions.length + 1;
        this.transactions.push(item);
    },

    reorderLines() {
        this.transactions.forEach((item, index) => {
            item.sr_no = index + 1;
        });
    },

    calculateLineTotal(index) {
        let item = this.transactions[index];
        item.total_cost = (parseFloat(item.qty) || 0) * (parseFloat(item.unit_cost) || 0);
        $wire.calculateTotals();
    }
}">

    {{-- Header with Add Button --}}
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Line Items</h5>
            <p class="mb-0 text-muted small">Add services or items with quantities and prices</p>
        </div>
        <button type="button" @click="addLine()" class="btn btn-primary btn-sm">
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
                <template x-for="(item, index) in transactions" :key="item.temp_id">
                    <tr>
                        {{-- Sr No --}}
                        <td class="text-center align-middle">
                            <span x-text="item.sr_no" class="fw-bold"></span>
                        </td>

                        {{-- Description --}}
                        <td>
                            <textarea x-model="item.description" @input="$wire.set('transactions.' + index + '.description', item.description)"
                                class="form-control form-control-sm" rows="2" placeholder="Enter service description..." maxlength="500"></textarea>
                        </td>

                        {{-- Quantity --}}
                        <td>
                            <input type="number" x-model="item.qty"
                                @input="calculateLineTotal(index); $wire.set('transactions.' + index + '.qty', item.qty)"
                                class="form-control form-control-sm text-end" step="0.001" min="0.001"
                                placeholder="1.000">
                        </td>

                        {{-- UOM --}}
                        <td>
                            <div class="input-group input-group-sm">
                                <select x-model="item.uom_id"
                                    @change="$wire.set('transactions.' + index + '.uom_id', item.uom_id)"
                                    class="form-select form-select-sm">
                                    <option value="">-- Select --</option>
                                    <template x-for="uom in uoms" :key="uom.id">
                                        <option :value="uom.id" x-text="uom.code"></option>
                                    </template>
                                </select>
                                <button type="button" wire:click="openQuickAddUOM"
                                    class="btn btn-outline-secondary btn-sm" title="Add UOM">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                        </td>

                        {{-- Unit Cost --}}
                        <td>
                            <input type="number" x-model="item.unit_cost"
                                @input="calculateLineTotal(index); $wire.set('transactions.' + index + '.unit_cost', item.unit_cost)"
                                class="form-control form-control-sm text-end" step="0.01" min="0"
                                placeholder="0.00">
                        </td>

                        {{-- Total Cost (Calculated) --}}
                        <td class="align-middle">
                            <strong x-text="parseFloat(item.total_cost || 0).toFixed(2)" class="text-primary"></strong>
                        </td>

                        {{-- Actions --}}
                        <td class="text-center align-middle">
                            <div class="btn-group btn-group-sm">
                                <button type="button" @click="duplicateLine(index)" class="btn btn-outline-info"
                                    title="Duplicate">
                                    <i class="mdi mdi-content-copy"></i>
                                </button>
                                <button type="button" @click="removeLine(index)" class="btn btn-outline-danger"
                                    title="Remove" :disabled="transactions.length === 1">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Mobile Card View --}}
    <div class="d-lg-none">
        <template x-for="(item, index) in transactions" :key="item.temp_id">
            <div class="mb-3 shadow-sm card">
                <div class="py-2 card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Item #<span x-text="item.sr_no"></span></span>
                    <div class="btn-group btn-group-sm">
                        <button type="button" @click="duplicateLine(index)" class="btn btn-outline-info btn-sm"
                            title="Duplicate">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                        <button type="button" @click="removeLine(index)" class="btn btn-outline-danger btn-sm"
                            title="Remove" :disabled="transactions.length === 1">
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
                        <textarea x-model="item.description" @input="$wire.set('transactions.' + index + '.description', item.description)"
                            class="form-control form-control-sm" rows="3" placeholder="Enter service description..." maxlength="500"></textarea>
                    </div>

                    {{-- Quantity & UOM Row --}}
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                Qty <span class="text-danger">*</span>
                            </label>
                            <input type="number" x-model="item.qty"
                                @input="calculateLineTotal(index); $wire.set('transactions.' + index + '.qty', item.qty)"
                                class="form-control form-control-sm text-end" step="0.001" min="0.001"
                                placeholder="1.000">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">
                                UOM <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm">
                                <select x-model="item.uom_id"
                                    @change="$wire.set('transactions.' + index + '.uom_id', item.uom_id)"
                                    class="form-select form-select-sm">
                                    <option value="">-- Select --</option>
                                    <template x-for="uom in uoms" :key="uom.id">
                                        <option :value="uom.id" x-text="uom.code"></option>
                                    </template>
                                </select>
                                <button type="button" wire:click="openQuickAddUOM"
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
                            <input type="number" x-model="item.unit_cost"
                                @input="calculateLineTotal(index); $wire.set('transactions.' + index + '.unit_cost', item.unit_cost)"
                                class="form-control form-control-sm text-end" step="0.01" min="0"
                                placeholder="0.00">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Total</label>
                            <div class="p-2 rounded text-end bg-light">
                                <strong x-text="parseFloat(item.total_cost || 0).toFixed(2)"
                                    class="text-primary fs-5"></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Add Line Button (Mobile) --}}
        <button type="button" @click="addLine()" class="mb-3 btn btn-primary w-100">
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
            Total Line Items: <strong x-text="transactions.length"></strong>
        </span>
        <span class="fw-bold">
            Subtotal: {{ \App\Enums\Currency::from($currency)->symbol() }}<span
                wire:loading.remove>{{ number_format($subtotal, 2) }}</span>
            <span wire:loading wire:target="calculateTotals">
                <span class="spinner-border spinner-border-sm"></span>
            </span>
        </span>
    </div>

</div>

{{-- Keyboard Shortcuts Help --}}
<div class="mt-3 text-muted small">
    <i class="mdi mdi-keyboard-outline me-1"></i>
    <strong>Tips:</strong> Use Tab to navigate between fields. Totals calculate automatically.
</div>
