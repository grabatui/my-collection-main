import {signal} from "@preact/signals";


export let registerFormOpened = signal(false);
export let authFormOpened = signal(false);
export let sendResetPasswordFormOpened = signal(false);
export let resetPasswordFormOpened = signal(false);

export let menuProfileOpened = signal(false);

export let resetToken = signal(null)
