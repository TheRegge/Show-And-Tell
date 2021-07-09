import { ReactElement } from 'react'
import FactoryMapper from './FactoryMapper'
import { IFormItemCreator } from '../../../app/model'
export class FormPlaceHolderFactory {
  private factoryMapper: FactoryMapper

  constructor() {
    this.factoryMapper = new FactoryMapper()
  }

  create({ item, index }: IFormItemCreator): ReactElement {
    const { type } = item
    const factory = this.factoryMapper.factory(type)
    return factory.create({ item, index })
  }
}

