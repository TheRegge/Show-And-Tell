import React from 'react'
import RadioButton from '../../../components/ui/iconsvg/RadioButton'

const Story = {
  title: 'Components/ui/iconsvg/RadioButton',
  component: RadioButton,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <RadioButton {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
