import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailVariantComponent } from './detail-variant.component';

describe('DetailVariantComponent', () => {
  let component: DetailVariantComponent;
  let fixture: ComponentFixture<DetailVariantComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DetailVariantComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DetailVariantComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
