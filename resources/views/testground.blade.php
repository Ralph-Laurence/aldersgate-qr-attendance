@extends('layouts.backoffice.master')

@section('content')
    <x-flat-button as="btn-test" click="alert('hello')" theme="primary" text="Add" icon="fa-user-graduate"/>
@endsection