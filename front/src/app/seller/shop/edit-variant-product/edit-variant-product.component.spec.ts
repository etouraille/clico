import { ComponentFixture, TestBed } from '@angular/core/testing';

import { EditVariantProductComponent } from './edit-variant-product.component';

describe('EditVariantProductComponent', () => {
  let component: EditVariantProductComponent;
  let fixture: ComponentFixture<EditVariantProductComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ EditVariantProductComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(EditVariantProductComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
