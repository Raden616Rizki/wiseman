<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CRUD Generator - {{ config('app.name', 'Unknown') }}</title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: #666666 !important;
        }

        input.form-control,
        select.form-control {
            font-size: 11px !important;
        }

        input:focus,
        select:focus,
        button:focus {
            border-color: #059aad !important;
            box-shadow: 0 0 0 0.25rem rgba(0, 154, 173, 0.25) !important;
        }

        .input-model:focus {
            box-shadow: 0 4px 0.25rem rgba(0, 154, 173, 0.25) !important;
        }

        select.select-table {
            color: #5b6b79;
        }

        .text-primary {
            color: #009AAD !important;
        }

        .btn {
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #009AAD;
            border-color: #009AAD;
            transition: .2s ease color;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #007f96 !important;
            border-color: #007f96 !important;
        }

        .container {
            max-width: 100%;
            padding: 20px;
        }

        .container .navbar-brand img {
            height: 100%;
        }

        thead th {
            text-align: center;
            vertical-align: middle;
            font-weight: normal;
            color: #666666 !important;
        }

        .font-large {
            font-size: 16px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: auto 1fr;
            align-items: center;
            width: 100%;
        }

        .grid-files {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(85px, 1fr));
            gap: 4px;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 11px;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            padding: .25rem .5rem;
            cursor: pointer;
            transition: .3s ease;
        }

        .file-item i {
            font-size: 10px;
            margin-left: 4px;
            opacity: 0;
        }

        .file-item.checked {
            border-color: #666666;
        }

        .file-item.checked i {
            opacity: 1;
        }

        .fw-semi-bold {
            font-weight: 600;
        }

        .rounded-10px {
            border-radius: 10px;
        }

        .rounded-5px {
            border-radius: 5px;
        }

        .table-container {
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .table-custom-bordered th,
        .table-custom-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-custom-bordered thead th {
            border-bottom-width: 1px;
        }

        .table-custom-bordered tbody tr:first-child td {
            border-top: none;
        }

        .table-custom-bordered tbody tr:last-child td {
            border-bottom: none;
        }

        .table-custom-bordered tbody tr td:first-child,
        .table-custom-bordered thead th:first-child {
            border-left: none;
        }

        .table-custom-bordered tbody tr td:last-child,
        .table-custom-bordered thead th:last-child {
            border-right: none;
        }

        .required {
            position: relative;
        }

        .required::after {
            content: " *";
            position: absolute;
            top: 0;
            color: red;
            font-size: 0.8em;
            margin-left: 2px;
        }

        .line {
            width: 100%;
            height: 1px;
            background: #666666;
        }

        .add-column-button {
            position: absolute;
            padding: 0 10px;
            background: #ffffff;
            right: 50%;
            top: 50%;
            transform: translate(50%, -50%);
            font-weight: 600;
            cursor: pointer;
        }

        select.form-control, select option:first-child {
            color: #bec8d0;
        }

        input.form-control::placeholder {
            color: #bec8d0 !important;
        }

        .form-control::-webkit-input-placeholder,
        .form-control:-moz-placeholder,
        .form-control::-moz-placeholder,
        .form-control:-ms-input-placeholder {
            color: #bec8d0 !important;
        }

        .btn-close {
            display: none;
            position: absolute;
            right: 2px;
            top: 0;
            bottom: 2px;
            margin: auto;
            padding: 0 0 0 4px;
            height: calc(100% - 5px);
            background: #ffffff;
            font-size: 16px;
            color: #666666;
            opacity: 1;
            cursor: pointer;
            z-index: 99;
        }
    </style>
</head>
<body>
<div id="app">
    <main class="container py-4">
        <div class="d-flex justify-content-between">
            <div>
                <div class="w-100">
                    <div class="form-group d-flex w-auto mb-3">
                        <label for="table_name" class="d-block font-large fw-bold text-primary my-auto required"
                               style="min-width: 120px;">
                            Nama Model:
                        </label>
                        <input type="text" class="form-control form-control-sm input-model" id="table_name"
                               data-field="model_name" placeholder="Nama Model"
                               style="border: none;border-bottom: 1px solid #059aad;border-radius: 0;font-size: 16px !important;font-weight: 600;color: #059aad;">
                    </div>
                </div>
                <span class="fst-italic">*) Primary key akan generate otomatis, tidak perlu memasukkan kolom id untuk primary</span>
            </div>
            <div>
                <button id="btn-generate" class="btn btn-primary btn-sm px-3 py-2" style="font-size:12px">
                    Generate
                </button>
            </div>
        </div>
        <div class="p-3 rounded-10px shadow-lg mt-4">
            <div class="py-3 d-flex justify-content-between">
                <div class="d-flex" style="width: 600px">
                    <label class="my-auto me-4">Pilih Tabel</label>
                    <div class="position-relative d-flex align-items-center">
                        <select class="form-control form-control-sm select-table">
                            <option disabled>Pilih Tabel</option>
                            <option value="" selected>Buat Tabel Dari Awal</option>
                            @foreach($tables as $key => $value)
                                <option value="{{ $key }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div style="width: 100%;"></div>
                <div class="grid-container">
                    <label class="my-auto me-4 required">File terlampir</label>
                    <div class="grid-files">
                        <div class="file-item checked" value="helper">Helper
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="file-item checked" value="controller">Controller
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="file-item checked" value="request">Request
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="file-item checked" value="resource">Resource
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="file-item checked" value="routes">Routes
                            <i class="fas fa-check"></i>
                        </div>
                    </div>

                </div>
            </div>
            <div class="table-container rounded-5px table-responsive">
                <table class="table p-0 m-0 table-hover" id="dynamicTable">
                    <thead>
                    <tr>
                        <th class="delete-container" scope="col" rowspan="2" style="width: 30px;"></th>
                        <th scope="col" rowspan="2" style="min-width: 120px; border-right: 1px solid #dee2e6;">
                            <div class="required fw-semi-bold">Nama Kolom</div>
                        </th>
                        <th scope="col" rowspan="2" style="min-width: 120px; border-right: 1px solid #dee2e6;">
                            <div class="required fw-semi-bold">Filter</div>
                        </th>
                        <th scope="col" colspan="2" class="fw-semi-bold migration"
                            style="min-width: 300px; border-right: 1px solid #dee2e6;">Migration
                        </th>
                        <th scope="col" colspan="2" class="fw-semi-bold"
                            style="min-width: 300px; border-right: 1px solid #dee2e6;">Request
                        </th>
                        <th scope="col" colspan="3" class="fw-semi-bold"
                            style="min-width: 300px; border-right: 1px solid #dee2e6;">Parent
                        </th>
                    </tr>
                    <tr>
                        <th scope="col" style="min-width: 300px; border-right: 1px solid #dee2e6;"
                            class="fw-semi-bold migration">
                            <span class="required">Tipe Data</span>
                        </th>
                        <th scope="col" style="min-width: 300px; border-right: 1px solid #dee2e6;"
                            class="fw-semi-bold migration">
                            <span>Atribut</span>
                        </th>
                        <th scope="col" style="min-width: 300px; border-right: 1px solid #dee2e6;" class="fw-semi-bold">
                            <span class="required">Create</span>
                        </th>
                        <th scope="col" style="min-width: 300px; border-right: 1px solid #dee2e6;" class="fw-semi-bold">
                            <span class="required">Edit</span>
                        </th>
                        <th scope="col" style="max-width: 300px; border-right: 1px solid #dee2e6;" class="fw-semi-bold">
                            Model
                        </th>
                        <th scope="col" style="max-width: 300px; border-right: 1px solid #dee2e6;" class="fw-semi-bold">
                            Relation
                        </th>
                        <th scope="col" style="max-width: 300px" class="fw-semi-bold">Include CRUD</th>
                    </tr>
                    </thead>
                    <tbody style="border-top:1px solid;">
                    <tr class="template">
                        <td class="delete-container">
                            <button type="button" class="delete-row btn btn-outline-danger btn-sm py-1 px-2"
                                    style="font-size: 11px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                        <td style="border-right: 1px solid #dee2e6;">
                            <input type="text" class="form-control form-control-sm" placeholder="Nama Kolom"
                                   data-field="fields.0.column_name">
                        </td>
                        <td style="border-right: 1px solid #dee2e6;">
                            <div id="filter" class="d-flex justify-content-between">
                                <select class="form-control form-control-sm" data-type="filter"
                                        data-field="fields.0.filter">
                                    <option value="" selected disabled>Filter</option>
                                    @foreach($filters as $filter)
                                        <option value="{{ $filter }}">{{ $filter }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td style="border-right: 1px solid #dee2e6;" class="migration">
                            <div id="data-type" class="d-flex justify-content-between mb-2">
                                <div class="col-4 position-relative">
                                    <select class="form-control form-control-sm" data-type="data-type-select"
                                            data-field="fields.0.data_type.name">
                                        <option value="" selected disabled>Tipe Data</option>
                                        @foreach($dataTypes as $key => $value)
                                            <option value="{{ $key }}">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="w-auto my-auto">:</span>
                                <div class="col-7">
                                    <input type="text" class="option form-control form-control-sm" placeholder=""
                                           disabled data-field="fields.0.data_type.option">
                                </div>
                            </div>
                        </td>
                        <td style="border-right: 1px solid #dee2e6;" class="migration">
                            <div id="attribute" class="d-flex justify-content-between mb-2">
                                <div class="col-4 position-relative">
                                    <span type="button" class="btn-close">&times;</span>
                                    <select class="form-control form-control-sm multiple" data-type="attribute-select"
                                            data-field="fields.0.attribute[0].name">
                                        <option value="" selected disabled>Attribute</option>
                                        @foreach($attributes as $key => $value)
                                            <option value="{{ $key }}">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="w-auto my-auto">:</span>
                                <div class="col-7">
                                    <input type="text" class="option form-control form-control-sm" placeholder=""
                                           disabled data-field="fields.0.attribute[0].option">
                                </div>
                            </div>
                        </td>
                        <td style="border-right: 1px solid #dee2e6;">
                            <div id="create-validation" class="d-flex justify-content-between mb-2">
                                <div class="col-4 position-relative">
                                    <span type="button" class="btn-close">&times;</span>
                                    <select class="form-control form-control-sm multiple"
                                            data-type="create-validation-select"
                                            data-field="fields.0.create_request[0].name">
                                        <option value="" selected disabled>Create Validation</option>
                                        @foreach($requestValidations as $key => $value)
                                            <option value="{{ $key }}">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="w-auto my-auto">:</span>
                                <div class="col-7">
                                    <input type="text" class="option form-control form-control-sm" placeholder=""
                                           disabled data-field="fields.0.create_request[0].option">
                                </div>
                            </div>
                        </td>
                        <td style="border-right: 1px solid #dee2e6;">
                            <div id="edit-validation" class="d-flex justify-content-between mb-2">
                                <div class="col-4 position-relative">
                                    <span type="button" class="btn-close">&times;</span>
                                    <select class="form-control form-control-sm multiple"
                                            data-type="edit-validation-select"
                                            data-field="fields.0.update_request[0].name">
                                        <option value="" selected disabled>Edit Validation</option>
                                        @foreach($requestValidations as $key => $value)
                                            <option value="{{ $key }}">{{ $key }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="w-auto my-auto">:</span>
                                <div class="col-7">
                                    <input type="text" class="option form-control form-control-sm" placeholder=""
                                           disabled data-field="fields.0.update_request[0].option">
                                </div>
                            </div>
                        </td>
                        <td style="border-right: 1px solid #dee2e6;">
                            <div id="model" class="d-flex justify-content-between position-relative">
                                <span type="button" class="btn-close">&times;</span>
                                <select class="form-control form-control-sm" data-type="model"
                                        data-field="fields.0.relation.name">
                                    <option value="" selected disabled>Model</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model }}">{{ $model }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td style="border-right: 1px solid #dee2e6;">
                            <div id="relation" class="d-flex justify-content-between position-relative">
                                <span type="button" class="btn-close">&times;</span>
                                <select class="form-control form-control-sm" data-type="relation"
                                        data-field="fields.0.relation.relation">
                                    <option value="" selected disabled>Relation</option>
                                    @foreach($relations as $relation)
                                        <option value="{{ $relation }}">{{ $relation }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div id="include-crud" class="d-flex justify-content-between">
                                <select class="form-control form-control-sm" data-type="include-crud"
                                        data-field="fields.0.relation.include-crud" disabled>
                                    <option value="" selected disabled>Include CRUD</option>
                                    <option value="yes">Ya</option>
                                    <option value="no">Tidak</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 mb-5 position-relative">
                <div class="line"></div>
                <span class="add-column-button">Tambah Kolom Baru</span>
            </div>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/pluralize/pluralize.js"></script>
<script>

    let formModel;
    const tables = @json($tables);
    const dataTypes = @json($dataTypes);
    const attributes = @json($attributes);
    const requestValidations = @json($requestValidations);
    const models = @json($models);
    const relations = @json($relations);

    function setDefaultFormModel() {
        formModel = {
            'model_name': "",
            'table': "",
            'created_files': [],
            'fields': [{
                'column_name': "",
                'filter': "",
                'data_type': {'name': "", 'option': ""},
                'attribute': [{'name': "", 'option': ""}],
                'create_request': [{'name': "", 'option': ""}],
                'update_request': [{'name': "", 'option': ""}],
                'relation': {'name': "", 'relation': "", 'include-crud': ""}
            }]
        };
    }

    function checkDisableIncludeCrud(rowElement) {
        const modelValue = rowElement.querySelector('select[data-type="model"]').value;
        const relationValue = rowElement.querySelector('select[data-type="relation"]').value;
        return relationValue === '' || modelValue === '';
    }

    function handleSelectChange(select, data) {
        select.addEventListener('change', function () {
            setupPlaceholderColor(select);
            const container = this.closest('.d-flex');

            const inputField = container.querySelector('input');
            if (data[this.value]) {
                const item = data[this.value];
                inputField.disabled = !item.option;
                inputField.type = item.type || 'text';
                inputField.placeholder = item.placeholder || '';
                inputField.value = item.value || '';
            }

            const closeButton = container.querySelector('.btn-close');
            if (closeButton) {
                closeButton.style.display = this.value ? 'block' : 'none';
            }

            const rowElement = this.closest('tr');
            rowElement.querySelector('select[data-type="include-crud"]').disabled = checkDisableIncludeCrud(rowElement);
            if (select.dataset.type === 'model') {
                updateAllSelectOptions('select[data-type="model"]');
            } else {
                updateSelectMultipleOptions(this.closest('td'));
            }

            const allOptionsDisabled = Array.from(select.options).every(option => option.disabled);
            if (allOptionsDisabled) {
                return;
            }

            if (select.classList.contains('multiple')) {
                const parentContainer = container.parentElement;

                if (container === parentContainer.lastElementChild) {
                    const newContainer = container.cloneNode(true);
                    const newSelect = newContainer.querySelector('select');
                    newContainer.querySelector('.btn-close').style.display = 'none';
                    newSelect.value = '';
                    if (newSelect.classList.contains('is-invalid')) {
                        newSelect.classList.remove('is-invalid');
                    }

                    const newInputField = newContainer.querySelector('input');
                    newInputField.type = 'text'
                    newInputField.disabled = true;
                    newInputField.value = '';
                    newInputField.placeholder = '';

                    const fieldPath = newSelect.getAttribute('data-field');
                    const matches = fieldPath.match(/fields\.\d+\.(\w+)\[(\d+)]\.name/);

                    if (matches) {
                        const currentIndex = parseInt(matches[2]);
                        const newIndex = currentIndex + 1;
                        newSelect.setAttribute('data-field', fieldPath.replace(`[${currentIndex}]`, `[${newIndex}]`));
                        newInputField.setAttribute('data-field', newInputField.getAttribute('data-field').replace(`[${currentIndex}]`, `[${newIndex}]`));
                    }

                    parentContainer.appendChild(newContainer);
                    handleSelectChange(newSelect, data);

                    newContainer.querySelectorAll('input[data-field], select[data-field]').forEach(function (element) {
                        element.removeAttribute('style');
                        handleInputChange(element);
                    });

                    newContainer.querySelectorAll('.btn-close').forEach(function (button) {
                        setupClearButton(button);
                    });

                    if (matches && formModel.fields[0][matches[1]]) {
                        formModel.fields[getRowIndexFromElement(select)][matches[1]].push({
                            'name': "",
                            'option': ""
                        });
                    }
                }
            }
        });
    }

    function setupPlaceholderColor(element) {
        element.style.color = element.options[element.selectedIndex].index === 0 ? '#bec8d0' : '#5b6b79';
    }

    function setupClearButton(button) {
        button.addEventListener('click', function () {
            const container = this.closest('.d-flex');
            const parentContainer = container.parentElement;
            const select = container.querySelector('select');
            const input = container.querySelector('input');

            if (select.classList.contains('multiple') && parentContainer.children.length > 1) {
                const fieldPath = select.getAttribute('data-field');
                const matches = fieldPath.match(/fields\.\d+\.(\w+)\[(\d+)]\.name/);

                if (matches) {
                    const arrayName = matches[1];
                    const currentIndex = parseInt(matches[2]);

                    formModel.fields[getRowIndexFromElement(select)][arrayName].splice(currentIndex, 1);
                    console.log(formModel);
                    container.remove();

                    parentContainer.querySelectorAll('input, select').forEach((element, index) => {
                        element.setAttribute('data-field', fieldPath.replace(`[${currentIndex}]`, `[${index}]`));
                    });
                }
            } else {
                if (select.dataset.type === 'model' || select.dataset.type === 'relation') {
                    const includeCrud = select.closest('tr').querySelector('select[data-type="include-crud"]');
                    includeCrud.disabled = true;
                    setToDefaultSelect(includeCrud);
                    setToDefaultSelect(select);
                    const getSelectField = select.dataset.field.split('.');

                    if (select.dataset.type === 'model') {
                        formModel.fields[getSelectField[1]].relation.name = '';
                    } else if (select.dataset.type === 'relation') {
                        formModel.fields[getSelectField[1]].relation.relation = '';
                    }
                    formModel.fields[getSelectField[1]].relation['include-crud'] = '';
                    console.log(formModel);
                } else {
                    setToDefaultSelect(select);

                    const getSelectField = select.dataset.field.split('.');
                    if (getSelectField[2].includes('[')) {
                        const [arrayPart, index] = getSelectField[2].split('[').map((x, idx) => idx === 1 ? parseInt(x.replace(']', '')) : x);
                        formModel.fields[getRowIndexFromElement(select)][arrayPart][index] = {name: '', option: ''};
                    } else {
                        formModel.fields[getRowIndexFromElement(select)][getSelectField[2]] = {name: '', option: ''};
                    }
                    console.log(formModel);
                }
                if (input) {
                    input.value = '';
                    input.disabled = true;
                    input.placeholder = '';
                }
                button.style.display = 'none';
            }

            if (select.dataset.type === 'model') {
                updateAllSelectOptions('select[data-type="model"]');
            } else {
                updateSelectMultipleOptions(parentContainer);
            }
        });
    }

    function handleInputChange(element, fieldPath = null) {
        element.addEventListener('change', function () {
            const value = this.value;

            if (element.getAttribute('data-field') === 'model_name') {
                formModel.model_name = value;
            } else {
                const dataField = fieldPath || element.getAttribute('data-field');
                const fieldParts = dataField.split('.');
                let target = formModel;

                if (fieldParts[1] < formModel.fields.length) {
                    for (let i = 0; i < fieldParts.length - 1; i++) {
                        const part = fieldParts[i];

                        if (part.includes('[')) {
                            const [arrayPart, index] = part.split('[').map((x, idx) => idx === 1 ? parseInt(x.replace(']', '')) : x);
                            target = target[arrayPart][index];
                        } else {
                            target = target[part];
                        }
                    }

                    const lastPart = fieldParts[fieldParts.length - 1];

                    if (lastPart.includes('[')) {
                        const [arrayPart, index] = lastPart.split('[').map((x, idx) => idx === 1 ? parseInt(x.replace(']', '')) : x);
                        target[arrayPart][index] = {name: value, option: ''};
                    } else {
                        target[lastPart] = value;
                    }
                }
            }

            console.log(formModel);
        });
    }

    function clearInputFields(row) {
        row.querySelectorAll('input').forEach(function (input) {
            input.type = 'text';
            input.value = '';
            if (input.classList.contains('is-invalid')) {
                input.classList.remove('is-invalid');
            }
        });
        row.querySelectorAll('input.option').forEach(function (input) {
            input.placeholder = '';
            input.disabled = true;
        });
        row.querySelectorAll('.btn-close').forEach(function (button) {
            button.style.display = 'none';
        });
    }

    function handleSelectTable() {
        setDefaultFormModel();

        const addColumnButton = document.querySelector('.add-column-button').closest('.position-relative');
        const deleteContainers = document.querySelectorAll('.delete-container');
        const migrationColumns = document.querySelectorAll('.migration');
        const rows = document.querySelectorAll('tbody tr');

        clearSelect(rows[0]);

        if (rows.length > 1) for (let i = rows.length - 1; i > 0; i--) rows[i].remove();

        if (this.value === '') {
            formModel.table = '';
            const modelNameInput = document.querySelector(`input[data-field="model_name"]`);
            const columnNameInput = document.querySelector(`input[data-field="fields.0.column_name"]`);

            modelNameInput.value = '';
            columnNameInput.value = '';
            columnNameInput.disabled = false;

            addColumnButton.style.display = 'block';
            deleteContainers.forEach(element => element.style.display = 'table-cell');
            migrationColumns.forEach(element => element.style.display = 'table-cell');
            return;
        }

        const columns = Object.values(tables[this.value]);
        const modelName = setNameModel(this.value);

        formModel.model_name = modelName;
        formModel.table = this.value;
        formModel.fields[0].column_name = columns[0];

        const modelNameInput = document.querySelector(`input[data-field="model_name"]`);
        const columnNameInput = document.querySelector(`input[data-field="fields.0.column_name"]`);

        modelNameInput.value = modelName;
        columnNameInput.value = columns[0];
        columnNameInput.disabled = true;

        addColumnButton.style.display = 'none';
        deleteContainers.forEach(element => element.style.display = 'none');
        migrationColumns.forEach(element => element.style.display = 'none');

        columns.forEach((column, index) => {
            if (index > 0) {
                addNewRow(column);
            }
            formModel.fields[index].data_type.name = '';
            formModel.fields[index].data_type.option = '';
            formModel.fields[index].attribute[0].name = '';
            formModel.fields[index].attribute[0].option = '';
            if (formModel.fields[index].attribute.length > 1) {
                formModel.fields[index].attribute.splice(1, formModel.fields[index].attribute.length - 1);
            }
        });
        console.log(formModel);
    }

    function setNameModel(name) {
        return pluralize.singular(name.replace(/^(m_|t_)/, '').split('_').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(''));
    }

    function addNewRow(columnName = '') {
        const tbody = document.querySelector('#dynamicTable tbody');
        const newRow = tbody.rows[0].cloneNode(true);
        const rowIndex = formModel.fields.length;

        clearSelect(newRow);
        setDataField(newRow, rowIndex);

        tbody.appendChild(newRow);

        newRow.querySelectorAll('.delete-row').forEach(button => {
            setupDeleteButton(button);
        });

        newRow.querySelectorAll('.btn-close').forEach(button => {
            setupClearButton(button);
        });

        if (columnName !== '' && typeof columnName === 'string') {
            const columnInput = newRow.querySelector(`input[data-field="fields.${rowIndex}.column_name"]`);
            columnInput.value = columnName;
            columnInput.disabled = true;
        }

        formModel.fields.push({
            'column_name': typeof columnName === 'string' ? columnName : "",
            'filter': "",
            'data_type': {'name': "", 'option': ""},
            'attribute': [{'name': "", 'option': ""}],
            'create_request': [{'name': "", 'option': ""}],
            'update_request': [{'name': "", 'option': ""}],
            'relation': {'name': "", 'relation': ""}
        });

        console.log(formModel);
    }

    function setupDeleteButton(button) {
        button.addEventListener('click', function () {
            let tbody = document.querySelector('#dynamicTable tbody');

            if (tbody.children.length > 1) {
                const row = this.closest('tr');
                const rowIndex = Array.from(row.parentNode.children).indexOf(row);

                const removeRow = () => {
                    row.remove();
                    formModel.fields.splice(rowIndex, 1);
                    tbody.querySelectorAll('tr').forEach((tr, index) => setDataField(tr, index));
                };

                if (isEmptyFormModel(formModel.fields[rowIndex])) {
                    removeRow();
                } else {
                    Swal.fire({
                        title: "Hapus Kolom",
                        text: "Apakah kamu yakin untuk menghapus Kolom ini?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#009AAD",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Hapus",
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            removeRow();
                        }
                    });
                }
            } else {
                Swal.fire({
                    title: "Warning",
                    text: "Tersisa satu baris, tidak dapat dihapus!.",
                    icon: "warning",
                    confirmButtonColor: "#009AAD",
                    confirmButtonText: "OK"
                });
            }
        });
    }

    function setDataField(row, index) {
        row.querySelectorAll('input, select').forEach(element => {
            const field = element.getAttribute('data-field').replace(/fields\.\d+/, `fields.${index}`);
            element.setAttribute('data-field', field);

            if (element.getAttribute('data-type')) {
                handleSelectChange(element, getDataForType(element.getAttribute('data-type')));
            }
            handleInputChange(element, field);
        });
    }

    function getRowIndexFromElement(element) {
        const row = element.closest('tr');
        return Array.from(row.parentNode.children).indexOf(row);
    }

    function clearSelect(element) {
        element.querySelectorAll('select').forEach(select => {
            const container = select.closest('td');
            if (container) {
                Array.from(container.children).forEach((child, index) => {
                    if (index > 0) {
                        child.remove();
                    } else {
                        const select = child.querySelector('select');
                        if (select) {
                            setToDefaultSelect(select);
                        }
                    }
                });
            }
            clearInputFields(element);
        });
    }

    function setToDefaultSelect(select) {
        if (select) {
            select.selectedIndex = 0;
            select.style.color = '#bec8d0';
            if (select.classList.contains('is-invalid')) {
                select.classList.remove('is-invalid');
            }

            Array.from(select.options).forEach((option, index) => {
                if (index === 0) {
                    option.style.display = 'none';
                    option.disabled = true;
                } else if (select.dataset.type === 'model') {
                    option.style.color = '#5b6b79';
                } else {
                    option.style.color = '#5b6b79';
                    option.style.display = 'block';
                    option.disabled = false;
                }
            });
        }
    }

    function handelCheckFile(item) {
        item.addEventListener('click', function () {
            if (!item.classList.contains('checked') || document.querySelectorAll('.grid-files .file-item.checked').length > 1) {
                item.classList.toggle('checked');
                formModel.created_files = Array.from(document.querySelectorAll('.grid-files .file-item.checked')).map(function (file) {
                    return file.getAttribute('value');
                });
            } else {
                showNotificationGenerate('error', 'Error!', 'Minimal 1 File Terlampir.');
            }
            console.log(formModel.created_files);
        });
    }

    function checkValidation() {
        document.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));

        let isValid = true;

        const modelName = document.querySelector('input[data-field="model_name"]');
        if (formModel.model_name === '') {
            modelName.classList.add('is-invalid');
            showNotificationGenerate('error', 'Error!', 'Nama Model tidak boleh kosong.');
            isValid = false;
        }

        if (formModel.model_name.match(/[^a-zA-Z0-9]/)) {
            modelName.classList.add('is-invalid');
            showNotificationGenerate('error', 'Error!', 'Nama Model hanya boleh berisi huruf, dan angka.');
            isValid = false;
        }

        formModel.fields.forEach(function (field, index) {
            if (field.column_name === '') {
                document.querySelector(`input[data-field="fields.${index}.column_name"]`).classList.add('is-invalid');
                showNotificationGenerate('error', 'Error!', 'Nama Kolom tidak boleh kosong.');
                isValid = false;
            }

            if (field.filter === '') {
                document.querySelector(`select[data-field="fields.${index}.filter"]`).classList.add('is-invalid');
                showNotificationGenerate('error', 'Error!', 'Filter tidak boleh kosong.');
                isValid = false;
            }

            if (field.data_type.name === '' && formModel.table === '') {
                document.querySelector(`select[data-field="fields.${index}.data_type.name"]`).classList.add('is-invalid');
                showNotificationGenerate('error', 'Error!', 'Tipe Data tidak boleh kosong.');
                isValid = false;
            }

            if (field.create_request[0].name === '') {
                document.querySelector(`select[data-field="fields.${index}.create_request[0].name"]`).classList.add('is-invalid');
                showNotificationGenerate('error', 'Error!', 'Update Request tidak boleh kosong.');
                isValid = false;
            }

            if (field.update_request[0].name === '') {
                document.querySelector(`select[data-field="fields.${index}.update_request[0].name"]`).classList.add('is-invalid');
                showNotificationGenerate('error', 'Error!', 'Update Request tidak boleh kosong.');
                isValid = false;
            }
        });

        return isValid;
    }

    function generateCrud() {
        $.ajax({
            url: "{{ route('crud.generate.post') }}",
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formModel),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                showNotificationGenerate('success', 'Berhasil!', 'Operasi CRUD berhasil dibuat.');
                setTimeout(function () {
                    location.reload();
                }, 3000);
            },
            error: function (error) {
                showNotificationGenerate('error', 'Kesalahan!', 'Terjadi kesalahan saat membuat operasi CRUD.');
            }
        });
    }

    function updateSelectMultipleOptions(context) {
        const selects = context.querySelectorAll('.multiple');
        const selectedValues = new Set();

        selects.forEach(select => {
            if (select.value) {
                selectedValues.add(select.value);
            }
            if (select) {
                Array.from(select.options).slice(1).forEach(option => {
                    const isExist = selectedValues.has(option.value);
                    option.disabled = isExist;
                    option.style.display = isExist ? 'none' : 'block';
                });
            }
        });

        const row = context.closest('tr');
        const isText = row.querySelector('select[data-type="data-type-select"]').value === 'text';

        let isDefault = Array.from(row.querySelectorAll('select[data-type="attribute-select"]'))
            .some(select => select.value === 'default');

        row.querySelectorAll('select[data-type="attribute-select"]').forEach(select => {
            toggleOption([select], 'default', isText || isDefault);
        });

        row.querySelectorAll('select[data-type="data-type-select"]').forEach(select => {
            toggleOption([select], 'text', isDefault);
        });
    }

    function updateAllSelectOptions(select) {
        const selects = document.querySelectorAll(select);
        const selectedValues = new Set();

        selects.forEach(select => {
            if (select.value) {
                selectedValues.add(select.value);
            }
        });

        selects.forEach(select => {
            if (select) {
                Array.from(select.options).slice(1).forEach(option => {
                    const isExist = selectedValues.has(option.value);
                    option.disabled = isExist;
                    option.style.display = isExist ? 'none' : 'block';
                });
            }
        });
    }

    function toggleOption(selects, value, disable) {
        selects.forEach(select => {
            Array.from(select.options).forEach((option) => {
                if (option.value === value) {
                    option.disabled = disable;
                    option.style.display = disable ? 'none' : 'block';
                }
            });
        });
    }

    function isEmptyFormModel(field) {
        return (
            field.column_name.trim() === "" &&
            field.filter.trim() === "" &&
            field.data_type.name.trim() === "" &&
            field.data_type.option.trim() === "" &&
            field.attribute.every(attr => attr.name.trim() === "" && attr.option.trim() === "") &&
            field.create_request.every(req => req.name.trim() === "" && req.option.trim() === "") &&
            field.update_request.every(req => req.name.trim() === "" && req.option.trim() === "") &&
            field.relation.name.trim() === "" &&
            field.relation.relation.trim() === ""
        );
    }

    function getDataForType(type) {
        switch (type) {
            case 'data-type-select':
                return dataTypes;
            case 'attribute-select':
                return attributes;
            case 'create-validation-select':
            case 'edit-validation-select':
                return requestValidations;
            default:
                return {};
        }
    }

    function showNotificationGenerate(type, title, text) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: title,
            text: text,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        setDefaultFormModel();

        document.querySelectorAll('select').forEach(function (selectElement) {
            selectElement.addEventListener('change', function () {
                setupPlaceholderColor(selectElement);
            });
        });

        document.querySelectorAll('select').forEach(select => {
            setToDefaultSelect(select);
        });

        document.querySelectorAll('.grid-files .file-item').forEach(function (item) {
            handelCheckFile(item)
        });

        document.querySelector('.select-table').addEventListener('change', handleSelectTable);

        document.querySelector('.add-column-button').addEventListener('click', addNewRow);

        document.querySelectorAll('input[data-field], select[data-field]').forEach(function (element) {
            handleInputChange(element);
        });

        document.querySelectorAll('table select[data-type="data-type-select"]').forEach(function (select) {
            handleSelectChange(select, dataTypes);
        });

        document.querySelectorAll('table select[data-type="attribute-select"]').forEach(function (select) {
            handleSelectChange(select, attributes);
        });

        document.querySelectorAll('table select[data-type="create-validation-select"]').forEach(function (select) {
            handleSelectChange(select, requestValidations);
        });

        document.querySelectorAll('table select[data-type="edit-validation-select"]').forEach(function (select) {
            handleSelectChange(select, requestValidations);
        });

        document.querySelectorAll('table select[data-type="model"]').forEach(function (select) {
            handleSelectChange(select, models);
        });

        document.querySelectorAll('table select[data-type="relation"]').forEach(function (select) {
            handleSelectChange(select, relations);
        });

        document.querySelectorAll('.btn-close').forEach(function (button) {
            setupClearButton(button);
        });

        document.querySelectorAll('.delete-row').forEach(function (button) {
            setupDeleteButton(button);
        });

        $('#btn-generate').on('click', function () {
            if (checkValidation()) {
                generateCrud();
            }
        });
    });
</script>
</body>
</html>
