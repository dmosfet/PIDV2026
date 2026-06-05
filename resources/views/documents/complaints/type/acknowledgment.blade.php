@extends('documents.layouts.mail')

@section('subject')
    Accusé de réception de votre {{ strtolower($complaint->complaint_type_id->label()) }}
@endsection

@section('pdf_content')
    @include('documents.complaints.type.partials._acknowledgment_content')
@endsection
