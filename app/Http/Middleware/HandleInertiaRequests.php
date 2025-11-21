<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    protected $es = [
        'Act only according to that maxim whereby you can, at the same time, will that it should become a universal law.' => 'Actúa sólo de acuerdo con aquella máxima por la cual puedas, al mismo tiempo, querer que se convierta en una ley universal.',
        'An unexamined life is not worth living.' => 'Una vida no examinada no vale la pena ser vivida.',
        'Be present above all else.' => 'Sé presente por encima de todo.',
        'Do what you can, with what you have, where you are' => 'Haz lo que puedas, con lo que tienes, donde estás.',
        'Happiness is not something readymade. It comes from your own actions.' => 'La felicidad no es algo ya hecho. Proviene de tus propias acciones.',
        'He who is contented is rich.' => 'El que está contento es rico.',
        'I begin to speak only when I am certain what I will say is not better left unsaid.' => 'Empiezo a hablar sólo cuando estoy seguro de que lo que voy a decir no es mejor dejarlo sin decir.',
        'I have not failed. I\'ve just found 10,000 ways that won\'t work.' => 'No he fracasado. Simplemente he encontrado 10,000 maneras que no funcionan.',
        'If you do not have a consistent goal in life, you can not live it in a consistent way.' => 'Si no tienes un objetivo consistente en la vida, no puedes vivirla de manera consistente.',
        'It is never too late to be what you might have been.' => 'Nunca es demasiado tarde para ser lo que podrías haber sido.',
        'It is not the man who has too little, but the man who craves more, that is poor.' => 'No es el hombre que tiene poco, sino el hombre que anhela más, el que es pobre.',
        'It is quality rather than quantity that matters.' => 'Es la calidad, más que la cantidad, lo que importa.',
        'Knowing is not enough; we must apply. Being willing is not enough; we must do.' => 'Saber no es suficiente; debemos aplicar. Estar dispuesto no es suficiente; debemos hacer.',
        'Let all your things have their places; let each part of your business have its time.' => 'Deja que todas tus cosas tengan su lugar; deja que cada parte de tu negocio tenga su tiempo.',
        'Live as if you were to die tomorrow. Learn as if you were to live forever.' => 'Vive como si fueras a morir mañana. Aprende como si fueras a vivir para siempre.',
        'No surplus words or unnecessary actions.' => 'No hay palabras de más ni acciones innecesarias.',
        'Nothing worth having comes easy.' => 'Nada que valga la pena se consigue fácilmente.',
        'Order your soul. Reduce your wants.' => 'Ordena tu alma. Reduce tus deseos.',
        'People find pleasure in different ways. I find it in keeping my mind clear.' => 'Las personas encuentran placer de diferentes maneras. Yo lo encuentro manteniendo mi mente clara.',
        'Simplicity is an acquired taste.' => 'La simplicidad es un gusto adquirido.',
        'Simplicity is the consequence of refined emotions.' => 'La simplicidad es la consecuencia de las emociones refinadas.',
        'Simplicity is the essence of happiness.' => 'La simplicidad es la esencia de la felicidad.',
        'Simplicity is the ultimate sophistication.' => 'La simplicidad es la máxima sofisticación.',
        'Smile, breathe, and go slowly.' => 'Sonríe, respira y ve despacio.',
        'The only way to do great work is to love what you do.' => 'La única manera de hacer un gran trabajo es amar lo que haces.',
        'The whole future lies in uncertainty: live immediately.' => 'El futuro entero yace en la incertidumbre: vive inmediatamente.',
        'Very little is needed to make a happy life.' => 'Se necesita muy poco para hacer una vida feliz.',
        'Waste no more time arguing what a good man should be, be one.' => 'No pierdas más tiempo discutiendo lo que un buen hombre debería ser, sé uno.',
        'Well begun is half done.' => 'Bien comenzado es medio hecho.',
        'When there is no desire, all things are at peace.' => 'Cuando no hay deseo, todas las cosas están en paz.',
        'Walk as if you are kissing the Earth with your feet.' => 'Camina como si estuvieras besando la Tierra con tus pies.',
        'Because you are alive, everything is possible.' => 'Porque estás vivo, todo es posible.',
        'Breathing in, I calm body and mind. Breathing out, I smile.' => 'Inhalando, calmo cuerpo y mente. Exhalando, sonrío.',
        'Life is available only in the present moment.' => 'La vida está disponible solo en el momento presente.',
        'The best way to take care of the future is to take care of the present moment.' => 'La mejor manera de cuidar el futuro es cuidar el momento presente.',
        'Nothing in life is to be feared, it is only to be understood. Now is the time to understand more, so that we may fear less.' => 'Nada en la vida debe ser temido, solo debe ser entendido. Ahora es el momento de entender más, para que podamos temer menos.',
        'The biggest battle is the war against ignorance.' => 'La batalla más grande es la guerra contra la ignorancia.',
        'Always remember that you are absolutely unique. Just like everyone else.' => 'Siempre recuerda que eres absolutamente único. Al igual que todos los demás.',
        'You must be the change you wish to see in the world.' => 'Debes ser el cambio que deseas ver en el mundo.',
        'It always seems impossible until it is done.' => 'Siempre parece imposible hasta que se hace.',
        'We must ship.' => 'Debemos enviar.',
    ];

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Extraer mensaje y autor de la cita de forma segura
        $raw = '';
        $raw = (string) Inspiring::quotes()->random();
        $parts = explode('-', $raw, 2);
        $message = isset($parts[0]) ? trim($parts[0]) : '';
        $author = isset($parts[1]) ? trim($parts[1]) : '';

        // Buscar traducción; si no existe, usar la oración original como fallback
        $translated = $this->es[$message] ?? $message;

        $user = $request->user();

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => $translated, 'author' => $author],
            'auth' => [
                'user' => $user ? $user->load('client') : null,
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
