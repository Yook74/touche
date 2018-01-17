import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './teams.component.html'
})
export class JudgeContestDetailTeamsComponent implements FieldComponent {
    @Input() data: any;
}
