@extends('layouts.auth')

@section('content')
  <!-- Form -->
  <div class="section dark valign-wrapper">
    <div class="container">
      <form action="{{ URL::to('/auth/register') }}" method="POST">
        {{ csrf_field() }}
        <div class="row">
          <div class="col s12"><h2 class="section-title">Registrér</h2></div>
          @if (Session::has('message'))
            <div class="col s12">
              <div style="border: 1px solid darkred; background-color: pink; padding: 10px; margin-bottom: 1rem">
                {{ Session::get('message') }}
              </div>
            </div>
          @endif
          <div class="input-field col s6">
            <input id="name" type="text" name="name" required>
            <label for="name">Navn</label>
          </div>
          <div class="input-field col s6">
            <input id="email" type="email" name="email" required>
            <label for="email">Email</label>
          </div>
          <div class="input-field col s6">
            <input id="password" type="password" name="password" required>
            <label for="password">Kodeord</label>
          </div>
          <div class="input-field col s6">
            <input id="password_confirmation" type="password" name="password_confirmation" required>
            <label for="password_confirmation">Kodeord Igen</label>
          </div>
          <div class="col s12">
            <button class="waves-effect waves-light btn-large" type="submit">Registrér</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
