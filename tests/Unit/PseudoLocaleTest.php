<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Lang;
use Victorio\Support\Testing\PseudoLocale;
use Victorio\Support\Testing\PseudoTranslator;

/**
 * Enables the pseudo locale and adds a few lines to it (lines added before enable() belong to the
 * replaced translator; real packages load theirs from files, through the shared loader).
 */
function enableWithLines(): void
{
    PseudoLocale::enable();
    Lang::addLines([
        'demo.greeting' => 'Hello, :name.',
        'demo.days' => '{1} :count day|[2,*] :count days',
        'demo.contact' => 'Write to :email.',
    ], 'en');
}

it('swaps the translator only when enabled', function (): void {
    expect(app('translator'))->not->toBeInstanceOf(PseudoTranslator::class);

    PseudoLocale::enable();
    PseudoLocale::enable();

    expect(app('translator'))->toBeInstanceOf(PseudoTranslator::class);
});

it('wraps every resolved translation, with its replacements', function (): void {
    enableWithLines();

    expect(__('demo.greeting', ['name' => 'Anna']))->toBe('⟦Hello, Anna.⟧');
});

it('pluralises before wrapping', function (): void {
    enableWithLines();

    expect(trans_choice('demo.days', 1))->toBe('⟦1 day⟧')
        ->and(trans_choice('demo.days', 3))->toBe('⟦3 days⟧');
});

it('leaves a missing key unwrapped, so it is caught', function (): void {
    enableWithLines();

    expect(__('demo.missing'))->toBe('demo.missing')
        ->and(PseudoLocale::untranslated('<p>'.__('demo.missing').'</p>'))->toBe(['demo.missing']);
});

it('lists hard-coded text and user-facing attributes', function (): void {
    $html = '<html><head><title>⟦Title⟧</title><style>.x{}</style></head><body>'
        .'<p>⟦Translated⟧</p><p>Hard-coded</p><img alt="Logo"><input placeholder="Type here">'
        .'<button aria-label="⟦Close⟧" title="Open">1 2 3</button><script>var text = "ignored";</script></body></html>';

    expect(PseudoLocale::untranslated($html))->toBe(['Hard-coded', 'Logo', 'Type here', 'Open']);
});

it('allows data, longest first', function (): void {
    expect(PseudoLocale::untranslated('<p>Quote 42 · Quote</p>', ['Quote', 'Quote 42']))->toBe([]);
});

it('recognises a translation that contains markup', function (): void {
    enableWithLines();
    $sentence = str_replace('%email%', '<a href="mailto:x@acme.test">x@acme.test</a>', (string) __('demo.contact', ['email' => '%email%']));

    expect(PseudoLocale::untranslated('<p>'.$sentence.'</p>', ['x@acme.test']))->toBe([])
        ->and(PseudoLocale::untranslated('<p>Write to <a>x</a>.⟧</p>', ['x']))->toBe(['Write to']);
});

it('wraps translations a package loads from its files', function (): void {
    app('translator')->addNamespace('fixture', __DIR__.'/../Fixtures/lang');
    PseudoLocale::enable();

    expect(__('fixture::messages.hello'))->toBe('⟦Hello from a file.⟧');
});
