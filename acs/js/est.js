function showSozlesme(cardtype) {
    if (cardtype === 'visa1' || cardtype === 'visa2') {
        cardtype = 'visa';
    }
    window
        .open(
            'sozlesme/sozlesme_' + cardtype + '.html',
            'm',
            'height=430,width=430,scrollbars=yes,menubar=no,resizable=yes,toolbar=no,location=no,status=no');
}

var x;

function showHelp(choice) {
    x = choice;
    window
        .open(
            'help/help.html?choise=' + choice,
            'm',
            'height=400,width=400,scrollbars=yes,menubar=no,resizable=yes,toolbar=no,location=no,status=no');
    xClicked = true;
}

function panCharEntered(t, n) {
    if (t.value.length === 4) {
        document.getElementById(n).focus();
    }
}

function encryptCardDigits() {

}

function getValueToBeEncrypted() {
    var valueToBeEncrypted = "";
    for (var i = 1; i <= 4; i++) {
        var el = document.getElementById("cardPassDigit" + i);
        if (el !== undefined && el !== null) {
            if (el.value !== '*')
                valueToBeEncrypted += i + "" + el.value;
        }
    }
    return valueToBeEncrypted;
}