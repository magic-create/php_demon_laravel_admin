@extends('admin::preset.panel')
@section('container.menu')
    <div class="mail-list">
        <a href="javascript:" class="active">Menu-1</a>
        <a href="javascript:">Menu-2</a>
        <a href="javascript:">Menu-3</a>
        <a href="javascript:">Menu-4</a>
        <a href="javascript:">Menu-5</a>
    </div>
@endsection
@section('container.content')
    <div class="p-3">
        <h4 class="mt-0 header-title">Textual inputs</h4>
        <p class="text-white-50 m-b-30">
            Here are examples of <code class="highlighter-rouge">.form-control</code>
            applied to each textual HTML5 <code class="highlighter-rouge">&lt;input&gt;</code>
            <code class="highlighter-rouge">type</code>.
        </p>
        <div class="form-group row">
            <label for="example-text-input" class="col-sm-2 col-form-label">Text</label>
            <div class="col-sm-10">
                <input class="form-control" type="text" value="Artisanal kale" id="example-text-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-search-input" class="col-sm-2 col-form-label">Search</label>
            <div class="col-sm-10">
                <input class="form-control" type="search" value="How do I shoot web" id="example-search-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-email-input" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input class="form-control" type="email" value="bootstrap@example.com" id="example-email-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-url-input" class="col-sm-2 col-form-label">URL</label>
            <div class="col-sm-10">
                <input class="form-control" type="url" value="https://getbootstrap.com" id="example-url-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-tel-input" class="col-sm-2 col-form-label">Telephone</label>
            <div class="col-sm-10">
                <input class="form-control" type="tel" value="1-(555)-555-5555" id="example-tel-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-password-input" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input class="form-control" type="password" value="hunter2" id="example-password-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-number-input" class="col-sm-2 col-form-label">Number</label>
            <div class="col-sm-10">
                <input class="form-control" type="number" value="42" id="example-number-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-datetime-local-input" class="col-sm-2 col-form-label">Date and time</label>
            <div class="col-sm-10">
                <input class="form-control" type="datetime-local" value="2011-08-19T13:45:00" id="example-datetime-local-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-date-input" class="col-sm-2 col-form-label">Date</label>
            <div class="col-sm-10">
                <input class="form-control" type="date" value="2011-08-19" id="example-date-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-month-input" class="col-sm-2 col-form-label">Month</label>
            <div class="col-sm-10">
                <input class="form-control" type="month" value="2011-08" id="example-month-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-time-input" class="col-sm-2 col-form-label">Time</label>
            <div class="col-sm-10">
                <input class="form-control" type="time" value="13:45:00" id="example-time-input">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-color-input" class="col-sm-2 col-form-label">Color</label>
            <div class="col-sm-10">
                <input class="form-control" type="color" value="#7a6fbe" id="example-color-input">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Select</label>
            <div class="col-sm-10">
                <select class="form-control">
                    <option>Select</option>
                    <option>Large select</option>
                    <option>Small select</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Custom Select</label>
            <div class="col-sm-10">
                <select class="custom-select">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="example-text-input-lg" class="col-sm-2 col-form-label">Large</label>
            <div class="col-sm-10">
                <input class="form-control form-control-lg" type="text" placeholder=".form-control-lg" id="example-text-input-lg">
            </div>
        </div>
        <div class="form-group row">
            <label for="example-text-input-sm" class="col-sm-2 col-form-label">Small</label>
            <div class="col-sm-10">
                <input class="form-control form-control-sm" type="text" placeholder=".form-control-sm" id="example-text-input-sm">
            </div>
        </div>
    </div>
@endsection
