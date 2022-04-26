@extends('layouts.app')

@section('sidebar')
  @include('layouts.sidebar')
@endsection

@section('content')
  <div class='container m-0'>

    @if (isset($greeting))
      <div class='alert alert-primary' role='alert'>{{ $greeting }}</div>
    @elseif(isset($failing))
      <div class='alert alert-danger' role='alert'>{{ $failing }}</div>
    @endif

    <h1 class='h4 m-1'>Dealings</h1>

    <div class='container'>
      <a href='{{ route('dealing.new') }}' class='btn btn-primary btn-sm active' role='button'
        aria-pressed='true'>+</a>
    </div>

    <table class='table table-hover mt-2'>
      <tr>
        <th>Domain Name</th>
        <th>Client Name</th>
        <th>Subtotal</th>
        <th>Billing Date</th>
        <th>Interval</th>
        <th>Auto Update</th>
        <th>Action</th>
      </tr>
      @foreach ($domains as $domain)
        @foreach ($domain->domainDealings as $domainDealing)
          <tr>
            <td>
              {{ $domain->name }}
            </td>

            <td>
              {{ $domainDealing->client->name }}
            </td>

            <td>
              {{ $domainDealing->subtotal }}
            </td>

            <td>
              {{ $domainDealing->billing_date }}
            </td>

            <td>
              {{ $domainDealing->interval }}
              {{ $domainDealing->interval_category }}
            </td>

            <td>
              {{ $domainDealing->is_auto_update }}
            </td>

            <td>
            </td>
        @endforeach
      @endforeach

    </table>

  </div>
@endsection