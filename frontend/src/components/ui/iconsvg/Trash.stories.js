import React from 'react'
import Trash from '../../../components/ui/iconsvg/Trash'

const Story = {
  title: 'Components/ui/iconsvg/Trash',
  component: Trash,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <Trash {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
