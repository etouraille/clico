import { ComponentFixture, TestBed } from '@angular/core/testing';

import { VariantAutocompleteComponent } from './variant-autocomplete.component';

describe('VariantAutocompleteComponent', () => {
  let component: VariantAutocompleteComponent;
  let fixture: ComponentFixture<VariantAutocompleteComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ VariantAutocompleteComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(VariantAutocompleteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
