@extends('layouts.welcome')

@section('content')
    <div class="header full-height horizontal-half-transition">
        <div class="background">
            <img src="images/hero.jpg" alt="">
        </div>
        <div class="phone-preview-sizer">
            <div class="phone-preview"></div>
            <div class="image-container active" style="background-image:url(images/sidebar.png)"></div>
            <div class="image-container" style="background-image:url(images/sidebar.png)"></div>
            <div class="image-container" style="background-image:url(images/sidebar.png)"></div>
        </div>
        <div class="horizontal-half-wrapper right-side active">
            <div class="header-background white"></div>
            <div class="header-wrapper row valign-wrapper">
                <div class="col s12 m8 offset-m2 valign">
                    <h1 class="purple-text">Coingo.dk</h1>
                    <span class="tagline">Det nye lagerstyringssystem med fakturering og regnskab</span>
                    <button class="read-more"><i class="icon-caret-down purple-text"></i></button>
                </div>
            </div>
        </div>
        <div class="horizontal-half-wrapper">
            <div class="header-background white"></div>
            <div class="header-wrapper row valign-wrapper">
                <div class="col s12 m8 offset-m2 valign">
                    <h1 class="purple-text" style="font-size: 4rem">Responsiv!</h1>
                    <span class="tagline">Appen er fuldt responsiv. Du kan se den på PC, Mac, tablet eller telefon.</span>
                    <button class="read-more"><i class="icon-caret-down purple-text"></i></button>
                </div>
            </div>
        </div>
        <div class="horizontal-half-wrapper right-side">
            <div class="header-background white"></div>
            <div class="header-wrapper row valign-wrapper">
                <div class="col s12 m8 offset-m2 valign">
                    <h1 class="purple-text">Billig!</h1>
                    <span class="tagline">Appen koster meget lidt i forhold til vores konkurrenter.</span>
                    <button class="read-more"><i class="icon-caret-down purple-text"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="section valign-wrapper">
        <div class="row valign">
            <div class="col s12 m10 offset-m1">
                <div class="row">
                    <div class="col s12"><h2 class="section-title">Features</h2></div>
                    <div class="col s12 m6 l4">
                        <h4><i class="icon-light-bulb"></i></h4>
                        <p class="caption">NYT! Lagerstyring! Opret Steder og Produkter, med varekode og Produktet kan tilkobles
                        til et Sted.</p>
                    </div>
                    <div class="col s12 m6 l4">
                        <h4><i class="icon-bolt"></i></h4>
                        <p class="caption">Opret Salgsfakturer, Kreditnotaer, Rykkere og Købsfakturer. Dette skal du have et aktivt
                        abonnement til.</p>
                    </div>
                    <div class="col s12 m6 l4">
                        <h4><i class="icon-rocket"></i></h4>
                        <p class="caption">Ufattelig hurtig! Appen er rigtig hurtig og alt foregår, uden at du skal refreshe siden.</p>
                    </div>
                    <div class="col s12 m6 l4">
                        <h4><i class="icon-settings"></i></h4>
                        <p class="caption">Når du opretter en Salgsfaktura, vil antallet blive automatisk trukket fra lageret,
                            og ført tilbage når du opretter en Kreditnota.</p>
                    </div>
                    <div class="col s12 m6 l4">
                        <h4><i class="icon-umbrella"></i></h4>
                        <p class="caption">Regnskab og Moms overblik. Får en kort overblik over dit regnskab og moms! Få at vide
                        om du skal betale moms eller få den igen!</p>
                    </div>
                    <div class="col s12 m6 l4">
                        <h4><i class="icon-graph"></i></h4>
                        <p class="caption">
                            Overskuelige Grafer! Du kan se overblikket over dit regnskab med en graf, der går 12 måneder tilbage!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Pricing Tables -->
    <div class="section valign-wrapper">
        <div class="row valign">
            <div class="col s12 m10 offset-m1">
                <div class="row">
                    <div class="col s12 m4">
                        <div class="pricing-table">
                            <div class="pricing-header">
                                <i class="icon-paper-plane"></i>
                                <h4>Månedlig</h4>
                                <div class="price">
                                    <span class="currency">DKK</span>
                                    <span class="dollars">99</span>
                                </div>
                            </div>
                            <ul class="pricing-features">
                                <li class="pricing-feature"><i class="icon-accept"></i>Steder</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Produkter</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Kunder</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Leverandører</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Salgs- & Købsfakturer</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Kreditnotaer</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Rykkere</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Send Fakturer på Email</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Regnskabs- & Momsoverblik</li>
                                <li class="pricing-feature"><a href="{{ URL::to('/register') }}">Opret Bruger</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col s12 m4">
                        <div class="pricing-table featured">
                            <div class="pricing-header">
                                <i class="icon-plane"></i>
                                <h4>Kvartal</h4>
                                <div class="price">
                                    <span class="currency">DKK</span>
                                    <span class="dollars">279</span>
                                </div>
                            </div>
                            <ul class="pricing-features">
                                <li class="pricing-feature"><i class="icon-accept"></i>Steder</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Produkter</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Kunder</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Leverandører</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Salgs- & Købsfakturer</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Kreditnotaer</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Rykkere</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Send Fakturer på Email</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Regnskabs- & Momsoverblik</li>
                                <li class="pricing-feature"><a href="{{ URL::to('/register') }}">Opret Bruger</a></li>

                            </ul>
                        </div>
                    </div>

                    <div class="col s12 m4">
                        <div class="pricing-table">
                            <div class="pricing-header">
                                <i class="icon-rocket"></i>
                                <h4>Årlig</h4>
                                <div class="price">
                                    <span class="currency">DKK</span>
                                    <span class="dollars">999</span>
                                </div>
                            </div>
                            <ul class="pricing-features">
                                <li class="pricing-feature"><i class="icon-accept"></i>Steder</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Produkter</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Kunder</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Leverandører</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Salgs- & Købsfakturer</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Kreditnotaer</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Rykkere</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Send Fakturer på Email</li>
                                <li class="pricing-feature"><i class="icon-accept"></i>Regnskabs- & Momsoverblik</li>
                                <li class="pricing-feature"><a href="{{ URL::to('/register') }}">Opret Bruger</a></li>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Team -->
    <div class="section full-height">
        <div class="row">
            <div class="col s12 m10 offset-m1 center">
                <h1>Manden Bag</h1>
                <div class="row">
                    <div class="col l4 offset-l4 m6 offset-m3">
                        <div class="card">
                            <div class="card-image">
                                <img src="images/mikolaj.jpg">
                            </div>
                        </div>
                        <h4 class="white-text">Mikolaj Marciniak</h4>
                        <span class="white-text">CEO</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Contact Us -->
    <div class="section light valign-wrapper">
        <div class="container">
            <form method="POST" action="{{ URL::to('contactMessages/send') }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col s12"><h2 class="section-title">Kontakt</h2></div>
                    <div class="input-field col s6">
                        <input id="name" type="text" name="name" required>
                        <label for="name">Navn</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="email" type="email" name="email" required>
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="message" class="materialize-textarea" name="body"></textarea>
                        <label for="message">Besked</label>
                        <button class="waves-effect waves-light btn-large" type="submit">Send</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
