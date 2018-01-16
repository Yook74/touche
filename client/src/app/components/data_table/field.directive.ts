import { Directive, ViewContainerRef } from '@angular/core';

@Directive({
    selector: '[table-field]'
})
export class FieldDirective {
    constructor(public viewContainerRef: ViewContainerRef) { }
}