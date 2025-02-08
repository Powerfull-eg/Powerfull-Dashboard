@php $colspan = count($columns) + ($actions ? 1 : 0); @endphp

<div class="card livewire-datatable" >
    <div class="card-header d-flex justify-content-end gap-2">
        <select class="form-select w-auto" wire:model.live="perPage">
            @foreach ($perPageOptions as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>

        @if ($searchable)
            <div class="input-icon">
                <input type="text" wire:model.live="search" class="form-control" placeholder="{{ __('Search...') }}" />
                <span class="input-icon-addon">
                    @include('livewire-datatable::icons.search')
                </span>
            </div>
        @endif

    </div>

    <div class="table-responsive">
        <table class="table table-vcenter card-table text-nowrap">
            <thead @class(['sticky-top' => $fixedHeader ?? ''])>
                <tr>
                    <th>#</th>
                    @foreach ($columns as $column)
                        <th style="width: {{ $column->width }}" @class([$column->class, 'sortable' => $column->sortable])>
                            @if ($column->sortable)
                                <a href="#" class="text-decoration-none text-muted" wire:click.prevent="sort('{{ $column->field }}')">
                                    {{ $column->label }}

                                    @php $icon = $sortField === $column->field ? ($sortDirection === 'asc' ? 'up' : 'down') : 'up'; @endphp
                                    @include("livewire-datatable::icons.chevron-$icon")
                                </a>
                            @else
                                {{ $column->label }}
                            @endif
                        </th>
                    @endforeach

                    @if ($actions)
                        <th style="width: 1%">{{ __('Actions') }}</th>
                    @endif
                </tr>
            </thead>

            <tbody wire:loading.class="opacity-50">
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $loop->index + 1  + (($paginators['page'] - 1)  * $rows->toArray()['per_page']) }}</td>
                        @foreach ($columns as $column)
                            
                            <td>{!! $column->value($row) ?: '-' !!}</td>
                        @endforeach

                        @if ($actions)
                            <td>
                                <div class="d-flex gap-1">
                                    @foreach ($actions as $action)
                                        {!! $action->render($row) !!}
                                    @endforeach
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $colspan }}" class="text-center py-5">
                            {{ __('No records found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <div class="my-1">{{ $rows->links() }}</div>
    </div>
</div>
