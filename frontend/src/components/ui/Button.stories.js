import React from 'react'
import Button from './Button'
import { Checkbox, all as allIcons } from './iconsvg'

const Story = {
  title: 'Components/ui/Button',
  component: Button,
  argTypes: {
    callback: { action: 'callback' },
    icon: {
      control: {
        type: null,
      },
    },
  },
}

const Template = (args) => <Button {...args} />
const Default = Template.bind({})
Default.args = {
  text: 'Default Button',
  variant: 'success',
}

const WithIcon = Template.bind({})
WithIcon.args = {
  text: 'With Checkbox Icon',
  icon: <Checkbox />,
  iconPosition: 'left',
}

const VariantsTemplate = (args) => (
  <div className="flex flex-wrap flex-row">
    <div className="m-2">
      <Button {...args} variant="primary" text="Primary" />
    </div>
    <div className="m-2">
      <Button {...args} variant="success" text="Success" />
    </div>
    <div className="m-2">
      <Button {...args} variant="info" text="Info" />
    </div>
    <div className="m-2">
      <Button {...args} variant="warning" text="warning" />
    </div>
    <div className="m-2">
      <Button {...args} variant="danger" text="Danger" />
    </div>
    <div className="m-2">
      <Button {...args} variant="neutral" text="Neutral" />
    </div>
  </div>
)

const Variants = VariantsTemplate.bind({})

const AllIconsButtonsTemplate = (args) => {
  const iconsList = Object.entries(allIcons)
  return (
    <div className="flex flex-wrap flex-row">
      {iconsList.map((comp) => {
        const [DisplayName] = comp
        const Component = allIcons[DisplayName]

        return (
          <div className="m-2" key={DisplayName}>
            <Button
              {...args}
              aria-label={`Button with ${DisplayName} icon`}
              text={DisplayName}
              icon={<Component />}
            />
          </div>
        )
      })}
    </div>
  )
}
const AllIconsButtons = AllIconsButtonsTemplate.bind({})
AllIconsButtons.args = {
  iconPosition: 'left',
}
export default Story
export { Default, Variants, WithIcon, AllIconsButtons }
