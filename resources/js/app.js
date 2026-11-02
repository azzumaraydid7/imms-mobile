//
// Mobile "More" sheet. Open/close only — the slide-up itself is CSS.
//
var sheet = document.querySelector('[data-sheet]');

if (sheet) {
    var setSheet = function (open) {
        sheet.hidden = ! open;
    };

    Array.prototype.forEach.call(document.querySelectorAll('[data-sheet-open]'), function (button) {
        button.addEventListener('click', function () {
            setSheet(true);
        });
    });

    Array.prototype.forEach.call(document.querySelectorAll('[data-sheet-close]'), function (button) {
        button.addEventListener('click', function () {
            setSheet(false);
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && ! sheet.hidden) {
            setSheet(false);
        }
    });
}

//
// Sign-in submit state. The API round-trip is a real network call, so the
// button locks and swaps to a spinner instead of leaving the tap unacknowledged.
//
var submitButton = document.querySelector('[data-submit]');
var submitForm = submitButton && submitButton.form;

if (submitForm) {
    submitForm.addEventListener('submit', function () {
        var spinner = submitForm.querySelector('[data-submit-spinner]');
        var label = submitForm.querySelector('[data-submit-label]');

        submitButton.disabled = true;

        if (spinner) {
            spinner.classList.remove('hidden');
        }

        if (label) {
            label.textContent = 'Signing in…';
        }
    });
}
