@extends('layouts.auth')

@section('content')
  <!-- Form -->
  <div class="section dark valign-wrapper">
    <div class="container">
      <form action="{{ URL::to('/auth/login') }}" method="POST">
        {{ csrf_field() }}

        <div class="row">
          <div class="col s12"><h2 class="section-title">Log Ind</h2></div>
          @if (Session::has('message'))
            <div class="col s12">
              <div style="border: 1px solid darkred; background-color: pink; padding: 10px; margin-bottom: 1rem">
                {{ Session::get('message') }}
              </div>
            </div>
          @endif
          <div class="input-field col s12">
            <input id="email" type="email" name="email" required>
            <label for="email">Email</label>
          </div>
          <div class="input-field col s12">
            <input id="password" type="password" name="password" required>
            <label for="password">Kodeord</label>
          </div>
          <div class="col s12">
            <button class="waves-effect waves-light btn-large" type="submit">Log Ind</button>
            <a href="{{ URL::to('/forgotPassword') }}" class="waves-effect waves-light btn-large">Glemt Kodeord?</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
