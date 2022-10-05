<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="{{url('css/index.css')}}">
    <link rel="icon" type="image/x-icon" href="{{url('images/snake.ico')}}">
    <script src="https://unpkg.co/gsap@3/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/baffle@0.3.6/dist/baffle.min.js"></script>
    <title>Snake Game!</title>
</head>

<body>
    <ul class="panels">
        <li class="panel"></li>
        <li class="panel"></li>
        <li class="panel"></li>
        <li class="panel"></li>
        <li class="panel"></li>
        <li class="panel"></li>
    </ul>

    <main class="page-main">
        <div>
            <h1 class="hacker-text">LET'S PLAY A GAME OF SNAKE...</h1>
        </div>
    </main>

    <script>
        const b = baffle('.hacker-text')
        b.set({
            characters: '░▒< ▒><>▓ <█░>▒ ▓█▒ ▓<▒/█ ▓░▓░ ▓▒░ █░▒░ █▓█░',
            speed: 180
        });
        setTimeout(b.start(), 3000);

        b.reveal(6500);

        const tl = gsap.timeline({ paused: true });

        tl.to(".panels .panel:first-child, .panels .panel:last-child", {
            scaleY: 1,
            duration: 1
        })
            .to(
                ".panels .panel:not(:first-child):not(:last-child)",
                { scaleY: 1 },
                "-=0.5"
            )
            .to(".panels .panel", {
                scaleY: 0,
                duration: 0.3,
                stagger: 0.05
            })
            .to(".panels", {
                clipPath: "circle(0%)",
                skewX: 0,
                duration: 1
            })
            .to(
                ".page-main",
                {
                    clipPath: "circle(100%)",
                    duration: 1
                },
                "-=0.3"
            );

        window.addEventListener("load", function () {
            tl.play();
        });

        // timer to change 
        setTimeout(() => window.location.href = 'snake-game', 6000)
    </script>
</body>
</html>
