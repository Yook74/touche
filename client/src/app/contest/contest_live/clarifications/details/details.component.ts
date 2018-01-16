import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './details.component.html'
})
export class LiveClarificationDetailsComponent implements FieldComponent {
    @Input() data: any;
}
