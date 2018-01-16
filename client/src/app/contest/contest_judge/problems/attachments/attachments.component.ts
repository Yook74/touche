import { Component, Input } from '@angular/core';
import { FieldComponent } from '../../../../components/data_table/field.component';

@Component({
    templateUrl: './attachments.component.html'
})
export class JudgeProblemAttachmentsComponent implements FieldComponent {
    @Input() data: any;
}
