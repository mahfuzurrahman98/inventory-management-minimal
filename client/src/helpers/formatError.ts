const formatError = (err: any) => {
    let errorMessage = '';
    if (err.response.status == 422) {
        if (typeof err.response.data.message === 'string') {
            errorMessage = err.response.data.message;
        } else {
            // if the message is an object, then we need to get the first key-value pair
            const key = Object.keys(err.response.data.message)[0];
            errorMessage = err.response.data.message[key][0];
        }
    } else {
        errorMessage = err.response.data.message;
    }
    return errorMessage;
};

export default formatError;
