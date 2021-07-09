import { SingleLineTextPlaceholderFactory } from './SingleLineTextPlacehoderFactory'
import { SingleCheckboxPlaceholderFactory } from './SingleCheckboxPlaceholderFactory'
import { ParagraphPlaceholderFactory } from './ParagraphPlaceholderFactory'

export class FactoryMapper {
  public factories: any

  constructor() {
    const singlelineTextFactory = new SingleLineTextPlaceholderFactory()
    const singleCheckboxFactory = new SingleCheckboxPlaceholderFactory()
    const paragraphFactory = new ParagraphPlaceholderFactory()

    this.factories = {}
    this.factories[singlelineTextFactory.type] = singlelineTextFactory
    this.factories[singleCheckboxFactory.type] = singleCheckboxFactory
    this.factories[paragraphFactory.type] = paragraphFactory
  }

  factory = (type: string) => type && this.factories[type]
}

export default FactoryMapper
