import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
    selector: 'live-sidenav',
    templateUrl: './sidenav.component.html',
    styleUrls: ['../../contest.component.css']
})
export class LiveSideNavComponent {
    constructor(private router: Router) { }
}
