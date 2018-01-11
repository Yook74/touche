import { Component, OnInit } from '@angular/core';
import { StandingService } from '../../services/model_services/standing.service';

@Component({
    templateUrl: './contest_live.component.html'
})
export class ContestLiveComponent implements OnInit {
    constructor(private service: StandingService) { }

    ngOnInit() {
    }
}