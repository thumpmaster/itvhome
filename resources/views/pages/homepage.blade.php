<!DOCTYPE HTML>
<html>
<head>
    <title>itv Homepage</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
</head>
<body class="homepage">
<div id="page-wrapper">
    <div id="header-wrapper">
        <div id="header">
            <img width="100" src="http://fetd.prod.cps.awseuwest1.itvcloud.zone/itvstatic/assets/images/brands/itv/itv-colour-pos.svg" />
        </div>
    </div>
    <div id="main-wrapper">
        <div class="container">
            @foreach($content as $key => $eachContent)
                <div class="row">
                    <div class="12u">
                        <section id="banner" style="background-image: url('{{ $eachContent['leaderContent']['heroImage'] }}')">
                            <header>
                                <h2>{{ $eachContent['leaderContent']['title'] }}</h2>
                                <p>{{ $eachContent['leaderContent']['description'] }}</p>
                            </header>
                        </section>
                        <section>
                            @foreach($eachContent['recommendedContent'] as $eachRecommendedSection)
                                <div class="row">
                                    @foreach($eachRecommendedSection as $eachRow)
                                        <div class="4u 12u(mobile)">
                                            <section class="box">
                                                <a href="#" class="image featured"><img src="{{ $eachRow['smallImage'] }}" alt="{{ $eachRow['title'] }}" /></a>
                                                <header>
                                                    <h3>{{ $eachRow['title'] }}</h3>
                                                </header>
                                                <p>{{ $eachRow['description'] }}</p>
                                            </section>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </section>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div id="footer-wrapper">
        <section id="footer" class="container">
            <div class="row">
                <div class="12u">
                    <div id="copyright">
                        <ul class="links">
                            <li>itv Homepage - sample</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
</html>
