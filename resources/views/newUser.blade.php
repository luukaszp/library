<!DOCTYPE html>
<html>
<head>
    <title>Zmiana hasła</title>
</head>

<body style="text-align: center">

<h2>Cześć, {{$user['name']}}!</h2>
<p>Pracownik biblioteki właśnie utworzył Twoje konto! </p>
<p>W celu bezpieczeństwa prosimy o przejście do strony, poprzez wciśnięcie przycisku poniżej. 
Zostaniesz poproszony o zmianę hasła.</p>
<p>Proszę pamiętać, aby hasło do konta spełniało następujące wymagania:</p>
<p>- 8 znaków</p>
<p>- co najmniej jedna duża litera, cyfra oraz znak specjalny</p>
<p>Życzymy przyjemnego czytania! </p>
<a href="{{route('first-login', $user->id)}}">KLIKNIJ TUTAJ</a>

</body>

</html>