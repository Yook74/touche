import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
    templateUrl: './contest.component.html',
    styleUrls: ['./contest.component.css']
})
export class ContestComponent implements OnInit {
    opened: boolean;
    routeName: string;

    constructor(private route: ActivatedRoute) {
        this.opened = true;
    }

    ngOnInit() {
        this.routeName = this.route.firstChild.snapshot.data['route'];
    }

    toggleMenu() {
        this.opened = !this.opened;
    }
}