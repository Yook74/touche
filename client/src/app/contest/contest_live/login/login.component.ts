import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from '../../../services/authentication.service';
import { CookieService } from 'ngx-cookie-service';

@Component({
    templateUrl: 'login.component.html'
})
export class ContestLiveLogin implements OnInit {
    username: string;
    password: string;

    constructor(private authenticationService: AuthenticationService, private cookieService: CookieService) { }

    ngOnInit() {

    }

    login() {
        this.authenticationService.teamLogin(this.username, this.password).subscribe((jwt: string) => {
            this.cookieService.set('Token', jwt);
        });
    }
}