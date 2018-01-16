import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './final_score.component.html'
})
export class JudgeStandingFinalScoreComponent implements FieldComponent {
    @Input() data: any;
}
