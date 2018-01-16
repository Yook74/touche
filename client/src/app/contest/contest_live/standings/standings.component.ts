import { Component, OnInit } from '@angular/core';
import { TableColumn } from '../../../models/table_column';
import { Standing } from '../../../models/standing';
import { StandingService } from '../../../services/model_services/standing.service';

@Component({
    templateUrl: './standings.component.html'
})
export class LiveStandingsComponent {
    tableColumns: TableColumn[] = [
        { header: 'Rank', dataField: 'rank', displayIsComponent: false, componentName: '' },
        { header: 'Team', dataField: 'teamName', displayIsComponent: false, componentName: '' },
        { header: 'Problems', dataField: 'problemsCompleted', displayIsComponent: true, componentName: '' },
        { header: 'Final Score', dataField: 'rank', displayIsComponent: true, componentName: '' }
    ];
    standings: Standing[];

    constructor(private service: StandingService) { }

    ngOnInit() {
        this.standings = this.service.getMockData();
    }
}
