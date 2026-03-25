@include('dashboard.errors')

@multilingualFormTabs
{{ BsForm::text('name') }}
{{ BsForm::textarea('description')->attribute('class', 'form-control textarea') }}
@endMultilingualFormTabs

{{ BsForm::price('price')->currency('SAR') }}


{{ BsForm::checkbox('active')->value(1)->checked(isset($fee) && $fee->active) }}
