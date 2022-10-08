<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Leaderboard</title>

        <link rel="stylesheet" href="css/app.css">
        <link rel="icon" type="image/x-icon" href="images/snake.ico">
    </head>
    <body>

        <div class="scrolling">
            <h1 class="title">
                <span class="icon">🐍</span>Top Agent Snakes<span class="icon">🐍</span>
            </h1>

            <div class="leaderboard-table">
                <header>
                    <h1 class="table-title">LEADERBOARD</h1>
                    <div class="blur"></div>
                </header>
                <table class="leaderboard">
                    <thead>
                        <th class="rank"></th>
                        <th class="username">USERNAME</th>
                        <th class="score">SCORE</th>
                        <th class="difficulty">DIFFICULTY</th>
                        <th class="date">DATE</th>
                    </thead>

                    <?php 
                      $rank = 1;
                    ?>
                    @foreach ($users as $user)
                        <?php $date = new DateTime($user->created_at)?>
                        <tbody>
                            <tr>
                                <td class="rank">{{$rank}}</td>
                                <td class="username">{{$user->name}}</td>
                                <td class="score">{{$user->score}}</td>
                                <td class="difficulty">{{$user->difficulty}}</td>
                                <td class="date">{{date_format($date, "d/m/Y")}}</td>
                            </tr>
                        </tbody>

                        <?php $rank += 1; ?>
                    @endforeach
                </table>
            </div>

            <div class="failed-message"></div>
            <div class="go-back">
                <button class="go-back-btn">GO BACK</button>
            </div>
        </div>

        <script type="text/javascript">
            // Go Back Button
            let goBackBtn = document.querySelector('.go-back-btn');

            goBackBtn.addEventListener('click', () => {
                window.location.href = '/';
            })
        </script>
    </body>
</html>