import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './time_remaining.component.html'
})
export class JudgeContestDetailTimeRemainingComponent implements FieldComponent {
    @Input() data: any;
}
