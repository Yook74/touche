import { Component, ComponentFactoryResolver, AfterViewInit, ViewChild, Input, Type } from '@angular/core';
import { FieldDirective } from './field.directive';
import { FieldComponent } from './field.component';

@Component({
    selector: 'data-table-field',
    template: '<ng-template table-field></ng-template>'
})
export class TableFieldComponent implements AfterViewInit {
    @Input() component: Type<any>;
    @Input() data: any;
    @ViewChild(FieldDirective) tableField: FieldDirective;

    constructor(private componentFactoryResolver: ComponentFactoryResolver) { }

    ngAfterViewInit() {
        if (this.component) this.loadComponent();
    }

    loadComponent() {
        let componentFactory = this.componentFactoryResolver.resolveComponentFactory(this.component);
        let viewContainerRef = this.tableField.viewContainerRef;
        let componentRef = viewContainerRef.createComponent(componentFactory);
        (<FieldComponent>componentRef.instance).data = this.data;
    }
}