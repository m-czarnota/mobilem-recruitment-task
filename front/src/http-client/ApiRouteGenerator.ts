import { httpClientData } from './index';

export class ApiRouteGenerator {
    public static generatePath(resourcePath: String, asLoggedUser: Boolean = false, withDebug: Boolean = false): String {
        let apiPath = httpClientData.apiUrl;
        
        if (asLoggedUser) {
            apiPath += `/user`;
        }

        apiPath += resourcePath;

        if (withDebug) {
            apiPath += '?XDEBUG_SESSION_START=PHPSTORM';
        }

        return apiPath;
    }
}