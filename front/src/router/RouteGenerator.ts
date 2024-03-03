import routes from './routes';

export class RouteGenerator {
    public static generateRoute(name: String): Object {
        const route: Array<Object> = routes[name];
        if (!route) {
            throw new RangeError(`Route with name ${name} doesn't exist`);
        }

        route['name'] = name;

        return route;
    }

    public static getAllRoutes(): Array<Object> {
        const allRoutes: Array<Object> = [];

        for (const [routeName, routeData] of Object.entries(routes)) {
            routeData['name'] = routeName;
            allRoutes.push(routeData);
        }

        return allRoutes;
    }

    public static getPath(name: String): String {
        const route: Array<Object> = routes[name];
        if (!route) {
            throw new RangeError(`Route with name ${name} doesn't exist`);
        }

        return route['path'];
    }
}