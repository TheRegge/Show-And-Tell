import React from 'react'
import List from '../../../components/ui/iconsvg/List'

const Story = {
  title: 'Components/ui/iconsvg/List',
  component: List,
  argTypes: {
    color: { control: 'color' },
  },
}

const Template = (args) => <List {...args} />
const Default = Template.bind({})
Default.args = {
  color: 'black',
}

export default Story
export { Default }
