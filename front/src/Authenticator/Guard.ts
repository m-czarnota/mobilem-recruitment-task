export class Guard {
    private static authenticationTokenCookieName = 'authentication-token';

    public static getToken() {
        if (document.cookie === '') {
            return undefined;
        }

        const cookies = document.cookie.split(/; */);
        const decodedSearchedCookieName = decodeURIComponent(Guard.authenticationTokenCookieName);

        for (const cookie of cookies) {
            const [cookieName, cookieVal] = cookie.split('=');

            if (cookieName === decodedSearchedCookieName) {
                return decodeURIComponent(cookieVal);
            }
        }
    }

    public static saveToken(token: String) {
        const cookieName = encodeURIComponent(Guard.authenticationTokenCookieName);
        const cookieVal = encodeURIComponent(token);
        let cookieText = cookieName + "=" + cookieVal;

        const data = new Date();
        data.setTime(data.getTime() + (15 * 60 * 1000));
        cookieText += "; expires=" + data.toUTCString();

        document.cookie = cookieText;
    }

    public static removeToken() {
        const cookieName = encodeURIComponent(Guard.authenticationTokenCookieName);
        document.cookie = cookieName + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
    }
}