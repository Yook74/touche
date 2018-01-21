import { Injectable } from "@angular/core";
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from "@angular/router";
import { AuthenticationService } from "./authentication.service";
import { CookieService } from "ngx-cookie-service";

@Injectable()
export class AdminAuthenticatedService implements CanActivate {
    constructor(private authService: AuthenticationService, private router: Router, private cookieService: CookieService) { }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): boolean {
        if (!this.authService.adminIsAuthenticated()) {
            this.router.navigate(['contest', route.parent.params['contestName'], 'admin-login']);
            return false;
        }
        this.cookieService.set('Token', this.cookieService.get('admin-Token'));
        return true;
    }
}