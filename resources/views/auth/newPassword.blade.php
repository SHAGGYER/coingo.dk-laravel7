@extends('layouts.auth')

@section('content')
  <!-- Form -->
  <div class="section dark valign-wrapper">
    <div class="container">
      <form action="{{ URL::to('/auth/resetPassword') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="row">
          <div class="col s12"><h2 class="section-title">Nyt Kodeord</h2></div>
          @if ($errors->any())
            <div class="col s12">
              <div style="border: 1px solid darkred; background-color: pink; padding: 10px; margin-bottom: 1rem">
                {{ $errors->first() }}
              </div>
            </div>
          @endif
          <div class="input-field col s12">
            <input id="password" type="password" name="password" required>
            <label for="password">Nyt Kodeord</label>
          </div>
          <div class="input-field col s12">
            <input id="password_confirmation" type="password" name="password_confirmation" required>
            <label for="password_confirmation">Kodeord Igen</label>
          </div>
          <div class="col s12">
            <button class="waves-effect waves-light btn-large" type="submit">Reset</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
